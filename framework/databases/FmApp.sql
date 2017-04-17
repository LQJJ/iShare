CREATE TABLE if not exists `fm_book` (
 `book_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY comment '【主键】',
 `info` varchar(6000)  comment '【内容介绍】',
 `book_name` varchar(50) UNIQUE KEY NOT NULL comment '【名称】',
 `time` varchar(30) NOT NULL comment '【更新时间】',
  `author` varchar(30) comment '【作者】',
  `category` varchar(30) comment '【分类】',
  status tinyint(1) unsigned default 0 comment '【0-更新中 1-完结】',
 `book_img` varchar(150) NOT NULL comment '【封面】'
);


CREATE TABLE if not exists `fm_bookurl` (
 `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY comment '【主键】',
 `book_name` varchar(50)  NOT NULL comment '【小说名称】',
 `url_name` varchar(30) NOT NULL comment '【当前名称】',
 `url_no` SMALLINT (5) UNIQUE KEY comment '【编号】',
 `book_url` varchar(150) NOT NULL comment '【音频链接】'
);