<?php
/**
 * System messages translation for CodeIgniter(tm)
 *
 * @author	CodeIgniter community
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$lang['db_invalid_connection_str'] = '无法根据送出的连接字串确定资料库设定';
$lang['db_unable_to_connect'] = '无法使用提供的设定连接到资料库服务器';
$lang['db_unable_to_select'] = '无法选择指定的资料库：%s';
$lang['db_unable_to_create'] = '无法创建指定的资料库：%s';
$lang['db_invalid_query'] = '送出的查询无效';
$lang['db_must_set_table'] = '查询中必须设定要查询的表名';
$lang['db_must_use_set'] = '更新资料请使用 Set 方法';
$lang['db_must_use_index'] = '必须指定索引以符合批次更新';
$lang['db_batch_missing_index'] = '批次更新作业中一个或多个行缺少指定的索引';
$lang['db_must_use_where'] = '更新作业必须包含 Where 条件';
$lang['db_del_must_use_where'] = '删除作业必须包含 Where 或 Like 条件';
$lang['db_field_param_missing'] = '取得栏位需要指定表名称';
$lang['db_unsupported_function'] = '您目前使用的资料库支援不支援此功能';
$lang['db_transaction_failure'] = '交易失败：执行回溯 (Rollback performed)';
$lang['db_unable_to_drop'] = '无法删除指定的资料库';
$lang['db_unsupported_feature'] = '您目前使用的资料库不支援此功能';
$lang['db_unsupported_compression'] = '服务器不支援您选择的档案压缩格式';
$lang['db_filepath_error'] = '送出的档案路径无法写入';
$lang['db_invalid_cache_path'] = '送出的站纯路径无效或无法写入';
$lang['db_table_name_required'] = '此操作需要指定表名称';
$lang['db_column_name_required'] = '此操作需要指定列名称';
$lang['db_column_definition_required'] = '此操作需要指定列定义';
$lang['db_unable_to_set_charset'] = '无法设定字元集：%s';
$lang['db_error_heading'] = '资料库发生错误';