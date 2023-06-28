<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Core\Helper;

use core\Constants\ErrorCode;
use Core\Exception\ErrorException;
use Core\Exception\JWTException;
use DateTimeImmutable;
use Exception;
use Hyperf\Contract\ConfigInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoder;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;

class JWTHelper
{
    protected Encoder $encoder;

    private array $supportAlgorithm = [
        // 非对称算法
        'RS256' => 'Lcobucci\JWT\Signer\Rsa\Sha256',
        'RS384' => 'Lcobucci\JWT\Signer\Rsa\Sha384',
        'RS512' => 'Lcobucci\JWT\Signer\Rsa\Sha512',
        'ES256' => 'Lcobucci\JWT\Signer\Ecdsa\Sha256',
        'ES384' => 'Lcobucci\JWT\Signer\Ecdsa\Sha384',
        'ES512' => 'Lcobucci\JWT\Signer\Ecdsa\Sha512',

        // 对称算法
        'HS256' => 'Lcobucci\JWT\Signer\Hmac\Sha256',
        'HS384' => 'Lcobucci\JWT\Signer\Hmac\Sha384',
        'HS512' => 'Lcobucci\JWT\Signer\Hmac\Sha512',
    ];

    private ConfigInterface $config;

    private $algorithm;

    private InMemory $signKey;

    private int $notBefore;

    private int $ttl;

    private string $issuedBy;

    private array $audience;

    public function __construct()
    {
        $this->config = ApplicationHelper::getConfig();
        $encoder = $this->config->get('jwt.encoder');
        $this->withEncoder(new $encoder());
        $this->withAlgorithm($this->config->get('jwt.alg'));
        $this->withSignKey(InMemory::base64Encoded($this->config->get('jwt.secret')));
        $this->withNotBefore(intval($this->config->get('jwt.not_before')));
        $this->withTTL(intval($this->config->get('jwt.ttl')));
        $this->withIssuedBy($this->config->get('jwt.issued_by'));
        $this->withAudience($this->config->get('jwt.audience'));
    }

    public function withAlgorithm(string $algorithm): self
    {
        if (! array_key_exists($algorithm, $this->supportAlgorithm)) {
            $algorithm = 'HS256';
        }

        $this->algorithm = new $this->supportAlgorithm[$algorithm]();
        return $this;
    }

    public function withSignKey(InMemory $inMemory): self
    {
        $this->signKey = $inMemory;
        return $this;
    }

    public function withNotBefore(int $second): self
    {
        $this->notBefore = $second;
        return $this;
    }

    public function withTTL(int $second): self
    {
        $this->ttl = $second;
        return $this;
    }

    public function withIssuedBy(string $issuedBy): self
    {
        $this->issuedBy = $issuedBy;
        return $this;
    }

    public function withAudience(string|array $audience): self
    {
        if (is_string($audience)) {
            $audience = explode(',', $audience);
        }

        $this->audience = array_filter(array_values($audience));
        return $this;
    }

    public function withEncoder(Encoder $encoder): self
    {
        $this->encoder = $encoder;
        return $this;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function getSignKey(): InMemory
    {
        return $this->signKey;
    }

    public function getNotBefore(): int
    {
        return $this->notBefore;
    }

    public function getTTL(): int
    {
        return $this->ttl;
    }

    public function getIssuedBy(): string
    {
        return $this->issuedBy;
    }

    public function getAudience(): array
    {
        return $this->audience;
    }

    public function getEncoder(): Encoder
    {
        return $this->encoder;
    }

    public function newConfiguration(): Configuration
    {
        return Configuration::forSymmetricSigner(
            $this->getAlgorithm(),
            $this->getSignKey(),
            $this->getEncoder()
        );
    }

    public function getToken($id, array $claims = [], array $header = []): string
    {
        $id = strval($id);
        $now = new DateTimeImmutable();
        $configuration = $this->newConfiguration();
        $builder = $configuration->builder(ChainedFormatter::default())
            ->issuedBy($this->getIssuedBy())
            ->permittedFor(...$this->getAudience())
            ->relatedTo($id)
            ->identifiedBy($id)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now->modify(sprintf('+%d second', $this->getNotBefore())))
            ->expiresAt($now->modify(sprintf('+%d second', $this->getTTL())));

        foreach ($claims as $name => $value) {
            $builder->withClaim($name, $value);
        }

        foreach ($header as $name => $value) {
            $builder->withHeader($name, $value);
        }

        $token = $builder->getToken($this->getAlgorithm(), $this->getSignKey());
        return $token->toString();
    }

    public function parseToken(string $token, ?Configuration $configuration = null): Token
    {
        if (! $configuration instanceof Configuration) {
            $configuration = $this->newConfiguration();
        }

        try {
            return $configuration->parser()->parse($token);
        } catch (CannotDecodeContent|Token\InvalidTokenStructure $exception) {
            throw new JWTException(t('jwt.not_token'));
        } catch (Exception $exception) {
            throw new JWTException($exception->getMessage());
        }
    }

    public function checkToken(string $token): bool|Token
    {
        $now = new DateTimeImmutable();
        $configuration = $this->newConfiguration();
        $token = $this->parseToken($token, $configuration);
        if (! method_exists($this->getAlgorithm(), 'algorithmId')) {
            throw new ErrorException(ErrorCode::SYSTEM_JWT_ALGORITHM_NOT_METHOD_ERROR);
        }

        // 算法是否一致
        if ($this->getAlgorithm()->algorithmId() != $configuration->signer()->algorithmId()) {
            return false;
        }

        // 签发者是否一致
        if (! $token->hasBeenIssuedBy($this->getIssuedBy())) {
            return false;
        }

        // 接收者是否符合要求
        $isNormalAudience = false;
        foreach ($this->getAudience() as $aud) {
            if ($token->isPermittedFor($aud)) {
                $isNormalAudience = true;
                break;
            }
        }
        if (! $isNormalAudience) {
            return false;
        }

        // TOKEN 是否达到开始生效时间
        if (! $token->isMinimumTimeBefore($now)) {
            return false;
        }

        // 是否已过期
        if ($token->isExpired($now)) {
            return false;
        }

        return $token;
    }

    public function getTokenClaims(Token $token): ?Token\DataSet
    {
        if (method_exists($token, 'claims')) {
            return $token->claims();
        }

        return null;
    }
}
