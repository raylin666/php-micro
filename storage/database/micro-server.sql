CREATE TABLE `account` (
   `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
   `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账号名称',
   `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账号密码',
   `real_username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '真实姓名',
   `sex` tinyint NOT NULL DEFAULT '0' COMMENT '性别 0未知 1男 2女',
   `birthday_at` datetime DEFAULT NULL COMMENT '出生时间',
   `avatar` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '账号头像',
   `phone_area` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0086' COMMENT '手机区号',
   `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号码',
   `email` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
   `current_login_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前最新登录TOKEN (可作为单点唯一登录标识)',
   `last_login_ip` int unsigned NOT NULL DEFAULT '0' COMMENT '最后登录IP',
   `status` tinyint NOT NULL DEFAULT '0' COMMENT '账号状态 0关闭 1开启 2冻结',
   `first_login_at` timestamp NULL DEFAULT NULL COMMENT '首次登录时间',
   `last_login_at` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
   `created_at` timestamp NOT NULL COMMENT '创建时间',
   `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
   `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
   PRIMARY KEY (`id`),
   UNIQUE KEY `uk_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统管理账号';

CREATE TABLE `article` (
   `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
   `title` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章标题',
   `author` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章作者',
   `summary` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章摘要',
   `cover` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章封面图片',
   `sort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '文章排序',
   `recommend_flag` tinyint NOT NULL DEFAULT '0' COMMENT '文章推荐标识 0:未推荐 1:已推荐',
   `commented_flag` tinyint NOT NULL DEFAULT '1' COMMENT '文章是否允许评论 0:不允许 1:允许',
   `status` tinyint NOT NULL DEFAULT '0' COMMENT '文章状态 0:已关闭 1:已开启',
   `view_count` int unsigned NOT NULL DEFAULT '0' COMMENT '文章浏览量',
   `comment_count` int unsigned NOT NULL DEFAULT '0' COMMENT '文章评论数',
   `collection_count` int unsigned NOT NULL DEFAULT '0' COMMENT '文章收藏量',
   `zan_count` int unsigned NOT NULL DEFAULT '0' COMMENT '文章点赞数',
   `share_count` int unsigned NOT NULL DEFAULT '0' COMMENT '文章分享数',
   `user_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '发布者编号ID',
   `last_commented_at` timestamp NULL DEFAULT NULL COMMENT '最新评论时间',
   `created_at` timestamp NOT NULL COMMENT '创建时间',
   `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
   `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
   PRIMARY KEY (`id`),
   KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章表';

CREATE TABLE `article_category` (
    `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
    `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '上级分类',
    `name` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
    `icon` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类图标',
    `color` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类颜色',
    `sort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '分类排序',
    `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '分类状态 0:已关闭 1:已开启',
    `created_at` timestamp NOT NULL COMMENT '创建时间',
    `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
    `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_name` (`name`),
    KEY `idx_pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章分类表';

CREATE TABLE `article_category_relation` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `article_id` bigint unsigned NOT NULL COMMENT '文章ID',
  `category_id` bigint unsigned NOT NULL COMMENT '文章分类ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_article_category` (`article_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章分类关系表';

CREATE TABLE `article_extend` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `article_id` bigint unsigned NOT NULL COMMENT '文章ID',
  `source` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章来源',
  `source_url` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章来源链接',
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章正文',
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文章关键词',
  `attachment_path` text COLLATE utf8mb4_unicode_ci COMMENT '文章附件路径',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_article` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章扩展表';

CREATE TABLE `chatbot_category_scene` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
  `pid` bigint NOT NULL DEFAULT '0' COMMENT '父级分类ID',
  `icon` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Icon 图标',
  `describe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类描述',
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '提问格式, 二级类目才有, 替换格式类似为 %s% 用来在传递提问时的占位值',
  `sort` smallint unsigned NOT NULL DEFAULT '0' COMMENT '分类排序',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '分类状态 0:已关闭 1:已开启',
  `created_at` timestamp NOT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name_pid` (`name`,`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='聊天机器人提问场景类型分类';

CREATE TABLE `upload_media` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
    `hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资源唯一哈希值',
    `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件名称',
    `domain` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件域名',
    `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件存储路径',
    `mime_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件类型',
    `size` int unsigned NOT NULL DEFAULT '0' COMMENT '文件存储大小',
    `ext` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件后缀',
    `extra` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '扩展内容, JSON 格式存储',
    `third_party_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第三方平台文件资源唯一哈希值',
    `third_party_uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第三方平台文件资源 UUID',
    `third_party` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT '上传平台 local(本地) | qiniu(七牛云)',
    `bucket` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '第三方平台文件资源存储仓库',
    `created_at` timestamp NOT NULL COMMENT '创建时间',
    `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
    `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_hash_third` (`hash`,`third_party`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='媒体资源上传';