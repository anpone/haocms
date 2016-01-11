<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Date Helpers
 * CodeIgniter日期助手
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/date_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('now'))
{
	/**
	 * Get "now" time
	 * “现在”的时间
	 * Returns time() based on the timezone parameter or on the 返回时间()或基于时区参数
	 * "time_reference" setting “time_reference”设置
	 *
	 * @param	string
	 * @return	int
	 */
	function now($timezone = NULL)
	{
		if (empty($timezone))
		{
			$timezone = config_item('time_reference');
		}

		if ($timezone === 'local' OR $timezone === date_default_timezone_get())
		{
			return time();
		}

		$datetime = new DateTime('now', new DateTimeZone($timezone));
		sscanf($datetime->format('j-n-Y G:i:s'), '%d-%d-%d %d:%d:%d', $day, $month, $year, $hour, $minute, $second);

		return mktime($hour, $minute, $second, $month, $day, $year);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('mdate'))
{
	/**
	 * Convert MySQL Style Datecodes
	 * 转换为Datecodes MySQL风格
	 * This function is identical to PHPs date() function,
	 * except that it allows date codes to be formatted using
	 * the MySQL style, where each code letter is preceded
	 * with a percent sign:  %Y %m %d etc...
	 * 这个函数是相同的php日期()函数,除了它允许格式化日期代码使用MySQL风格,其中每个代码字母是前百分之一的迹象:% Y % m % d等等……
	 * The benefit of doing dates this way is that you don't
	 * have to worry about escaping your text letters that
	 * match the date codes.
	 * 日期这样做的好处是,你不必担心逃离你的文本字母相匹配的日期码。
	 * @param	string
	 * @param	int
	 * @return	int
	 */
	function mdate($datestr = '', $time = '')
	{
		if ($datestr === '')
		{
			return '';
		}
		elseif (empty($time))
		{
			$time = now();
		}

		$datestr = str_replace(
			'%\\',
			'',
			preg_replace('/([a-z]+?){1}/i', '\\\\\\1', $datestr)
		);

		return date($datestr, $time);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('standard_date'))
{
	/**
	 * Standard Date
	 * 标准化日期 
	 * Returns a date formatted according to the submitted standard.
	 * 返回一个格式化的日期根据提交的标准。
	 * As of PHP 5.2, the DateTime extension provides constants that
	 * serve for the exact same purpose and are used with date().
	 * PHP 5.2,DateTime扩展提供了常数,为同样的目的和使用日期()。
	 * @todo	Remove in version 3.1+. 删除在版本
	 * @deprecated	3.0.0	Use PHP's native date() instead. 使用PHP的本地日期()。
	 * @link	http://www.php.net/manual/en/class.datetime.php#datetime.constants.types
	 *
	 * @example	date(DATE_RFC822, now()); // default 默认
	 * @example	date(DATE_W3C, $time); // a different format and time 不同的格式和时间
	 *
	 * @param	string	$fmt = 'DATE_RFC822'	the chosen format 选择格式
	 * @param	int	$time = NULL		Unix timestamp 时间戳
	 * @return	string
	 */
	function standard_date($fmt = 'DATE_RFC822', $time = NULL)
	{
		if (empty($time))
		{
			$time = now();
		}

		// Procedural style pre-defined constants from the DateTime extension 从DateTime扩展程序风格预定义常量
		if (strpos($fmt, 'DATE_') !== 0 OR defined($fmt) === FALSE)
		{
			return FALSE;
		}

		return date(constant($fmt), $time);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('timespan'))
{
	/**
	 * Timespan
	 * 时间间隔
	 * Returns a span of seconds in this format: 返回一个跨度秒的格式:
	 *	10 days 14 hours 36 minutes 47 seconds 10天14小时36分47秒
	 *
	 * @param	int	a number of seconds 一个的秒数
	 * @param	int	Unix timestamp UNIX时间 
	 * @param	int	a number of display units 显示设备
	 * @return	string
	 */
	function timespan($seconds = 1, $time = '', $units = 7)
	{
		$CI =& get_instance();
		$CI->lang->load('date');

		is_numeric($seconds) OR $seconds = 1;
		is_numeric($time) OR $time = time();
		is_numeric($units) OR $units = 7;

		$seconds = ($time <= $seconds) ? 1 : $time - $seconds;

		$str = array();
		$years = floor($seconds / 31557600);

		if ($years > 0)
		{
			$str[] = $years.' '.$CI->lang->line($years > 1 ? 'date_years' : 'date_year');
		}

		$seconds -= $years * 31557600;
		$months = floor($seconds / 2629743);

		if (count($str) < $units && ($years > 0 OR $months > 0))
		{
			if ($months > 0)
			{
				$str[] = $months.' '.$CI->lang->line($months > 1 ? 'date_months' : 'date_month');
			}

			$seconds -= $months * 2629743;
		}

		$weeks = floor($seconds / 604800);

		if (count($str) < $units && ($years > 0 OR $months > 0 OR $weeks > 0))
		{
			if ($weeks > 0)
			{
				$str[] = $weeks.' '.$CI->lang->line($weeks > 1 ? 'date_weeks' : 'date_week');
			}

			$seconds -= $weeks * 604800;
		}

		$days = floor($seconds / 86400);

		if (count($str) < $units && ($months > 0 OR $weeks > 0 OR $days > 0))
		{
			if ($days > 0)
			{
				$str[] = $days.' '.$CI->lang->line($days > 1 ? 'date_days' : 'date_day');
			}

			$seconds -= $days * 86400;
		}

		$hours = floor($seconds / 3600);

		if (count($str) < $units && ($days > 0 OR $hours > 0))
		{
			if ($hours > 0)
			{
				$str[] = $hours.' '.$CI->lang->line($hours > 1 ? 'date_hours' : 'date_hour');
			}

			$seconds -= $hours * 3600;
		}

		$minutes = floor($seconds / 60);

		if (count($str) < $units && ($days > 0 OR $hours > 0 OR $minutes > 0))
		{
			if ($minutes > 0)
			{
				$str[] = $minutes.' '.$CI->lang->line($minutes > 1 ? 'date_minutes' : 'date_minute');
			}

			$seconds -= $minutes * 60;
		}

		if (count($str) === 0)
		{
			$str[] = $seconds.' '.$CI->lang->line($seconds > 1 ? 'date_seconds' : 'date_second');
		}

		return implode(', ', $str);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('days_in_month'))
{
	/**
	 * Number of days in a month
	 * 在一个月内的天数
	 * Takes a month/year as input and returns the number of days
	 * for the given month/year. Takes leap years into consideration.
	 * 需要一个月/年作为输入并返回给定的天数月/年。考虑闰年。
	 * @param	int	a numeric month 一个数字月
	 * @param	int	a numeric year 数字的一年
	 * @return	int
	 */
	function days_in_month($month = 0, $year = '')
	{
		if ($month < 1 OR $month > 12)
		{
			return 0;
		}
		elseif ( ! is_numeric($year) OR strlen($year) !== 4)
		{
			$year = date('Y');
		}

		if (defined('CAL_GREGORIAN'))
		{
			return cal_days_in_month(CAL_GREGORIAN, $month, $year);
		}

		if ($year >= 1970)
		{
			return (int) date('t', mktime(12, 0, 0, $month, 1, $year));
		}

		if ($month == 2)
		{
			if ($year % 400 === 0 OR ($year % 4 === 0 && $year % 100 !== 0))
			{
				return 29;
			}
		}

		$days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		return $days_in_month[$month - 1];
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('local_to_gmt'))
{
	/**
	 * Converts a local Unix timestamp to GMT
	 * 将本地Unix时间戳转换为格林尼治时间
	 * @param	int	Unix timestamp UNIX时间 
	 * @return	int
	 */
	function local_to_gmt($time = '')
	{
		if ($time === '')
		{
			$time = time();
		}

		return mktime(
			gmdate('G', $time),
			gmdate('i', $time),
			gmdate('s', $time),
			gmdate('n', $time),
			gmdate('j', $time),
			gmdate('Y', $time)
		);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('gmt_to_local'))
{
	/**
	 * Converts GMT time to a localized value
	 * GMT时间转换为一个本地化值
	 * Takes a Unix timestamp (in GMT) as input, and returns
	 * at the local value based on the timezone and DST setting
	 * submitted
	 * 需要一个Unix时间戳(格林尼治时间)作为输入,并返回在当地的价值根据时区和DST设置提交
	 * @param	int	Unix timestamp UNIX时间戳
	 * @param	string	timezone 时区
	 * @param	bool	whether DST is active DST是否活跃
	 * @return	int
	 */
	function gmt_to_local($time = '', $timezone = 'UTC', $dst = FALSE)
	{
		if ($time === '')
		{
			return now();
		}

		$time += timezones($timezone) * 3600;

		return ($dst === TRUE) ? $time + 3600 : $time;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('mysql_to_unix'))
{
	/**
	 * Converts a MySQL Timestamp to Unix
	 * 将MySQL时间戳 变换为Unix
	 * @param	int	MySQL timestamp YYYY-MM-DD HH:MM:SS
	 * @return	int	Unix timstamp
	 */
	function mysql_to_unix($time = '')
	{
		// We'll remove certain characters for backward compatibility
		// since the formatting changed with MySQL 4.1
		// 我们会删除某些字符格式改变了使用MySQL 4.1以来的向后兼容性
		// YYYY-MM-DD HH:MM:SS

		$time = str_replace(array('-', ':', ' '), '', $time);

		// YYYYMMDDHHMMSS
		return mktime(
			substr($time, 8, 2),
			substr($time, 10, 2),
			substr($time, 12, 2),
			substr($time, 4, 2),
			substr($time, 6, 2),
			substr($time, 0, 4)
		);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('unix_to_human'))
{
	/**
	 * Unix to "Human"
	 * Unix“人类”
	 * Formats Unix timestamp to the following prototype: 2006-08-21 11:35 PM
	 * Unix时间戳格式如下原型:2006-08-21PM
	 * @param	int	Unix timestamp UNIX时间 
	 * @param	bool	whether to show seconds 是否显示秒
	 * @param	string	format: us or euro 格式:美元或欧元
	 * @return	string
	 */
	function unix_to_human($time = '', $seconds = FALSE, $fmt = 'us')
	{
		$r = date('Y', $time).'-'.date('m', $time).'-'.date('d', $time).' ';

		if ($fmt === 'us')
		{
			$r .= date('h', $time).':'.date('i', $time);
		}
		else
		{
			$r .= date('H', $time).':'.date('i', $time);
		}

		if ($seconds)
		{
			$r .= ':'.date('s', $time);
		}

		if ($fmt === 'us')
		{
			return $r.' '.date('A', $time);
		}

		return $r;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('human_to_unix'))
{
	/**
	 * Convert "human" date to GMT
	 * “人”日期转换为格林尼治时间
	 * Reverses the above process
	 * 逆转上述过程
	 * @param	string	format: us or euro 格式:美元或欧元
	 * @return	int
	 */
	function human_to_unix($datestr = '')
	{
		if ($datestr === '')
		{
			return FALSE;
		}

		$datestr = preg_replace('/\040+/', ' ', trim($datestr));

		if ( ! preg_match('/^(\d{2}|\d{4})\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr))
		{
			return FALSE;
		}

		sscanf($datestr, '%d-%d-%d %s %s', $year, $month, $day, $time, $ampm);
		sscanf($time, '%d:%d:%d', $hour, $min, $sec);
		isset($sec) OR $sec = 0;

		if (isset($ampm))
		{
			$ampm = strtolower($ampm);

			if ($ampm[0] === 'p' && $hour < 12)
			{
				$hour += 12;
			}
			elseif ($ampm[0] === 'a' && $hour === 12)
			{
				$hour = 0;
			}
		}

		return mktime($hour, $min, $sec, $month, $day, $year);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('nice_date'))
{
	/**
	 * Turns many "reasonably-date-like" strings into something
	 * that is actually useful. This only works for dates after unix epoch.
	 * 许多“reasonably-date-like”字符串变成实际上是有用的东西。这只适用于unix纪元后日期。
	 * @param	string	The terribly formatted date-like string 非常格式化日期字符串
	 * @param	string	Date format to return (same as php date function) 日期格式返回(php日期函数一样)
	 * @return	string
	 */
	function nice_date($bad_date = '', $format = FALSE)
	{
		if (empty($bad_date))
		{
			return 'Unknown';
		}
		elseif (empty($format))
		{
			$format = 'U';
		}

		// Date like: YYYYMM
		if (preg_match('/^\d{6}$/i', $bad_date))
		{
			if (in_array(substr($bad_date, 0, 2), array('19', '20')))
			{
				$year  = substr($bad_date, 0, 4);
				$month = substr($bad_date, 4, 2);
			}
			else
			{
				$month  = substr($bad_date, 0, 2);
				$year   = substr($bad_date, 2, 4);
			}

			return date($format, strtotime($year.'-'.$month.'-01'));
		}

		// Date Like日期如: YYYYMMDD
		if (preg_match('/^(\d{2})\d{2}(\d{4})$/i', $bad_date, $matches))
		{
			return date($format, strtotime($matches[1].'/01/'.$matches[2]));
		}

		// Date Like: MM-DD-YYYY __or__ M-D-YYYY (or anything in between或者介于两者之间的任何东西)
		if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/i', $bad_date, $matches))
		{
			return date($format, strtotime($matches[3].'-'.$matches[1].'-'.$matches[2]));
		}

		// Any other kind of string, when converted into UNIX time, 其它类型的字符串,当转换为UNIX时间,
		// produces "0 seconds after epoc..." is probably bad... 产生“0秒后epoc……“可能是坏……
		// return "Invalid Date". 返回“无效日期”。
		if (date('U', strtotime($bad_date)) === '0')
		{
			return 'Invalid Date';
		}

		// It's probably a valid-ish date format already  这可能是一个有效的日期格式了
		return date($format, strtotime($bad_date));
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('timezone_menu'))
{
	/**
	 * Timezone Menu
	 * 时区菜单
	 * Generates a drop-down menu of timezones.
	 * 生成一个时区的下拉菜单。
	 * @param	string	timezone 时区
	 * @param	string	classname 类别名称
	 * @param	string	menu name 菜单名
	 * @param	mixed	attributes 属性
	 * @return	string
	 */
	function timezone_menu($default = 'UTC', $class = '', $name = 'timezones', $attributes = '')
	{
		$CI =& get_instance();
		$CI->lang->load('date');

		$default = ($default === 'GMT') ? 'UTC' : $default;

		$menu = '<select name="'.$name.'"';

		if ($class !== '')
		{
			$menu .= ' class="'.$class.'"';
		}

		$menu .= _stringify_attributes($attributes).">\n";

		foreach (timezones() as $key => $val)
		{
			$selected = ($default === $key) ? ' selected="selected"' : '';
			$menu .= '<option value="'.$key.'"'.$selected.'>'.$CI->lang->line($key)."</option>\n";
		}

		return $menu.'</select>';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('timezones'))
{
	/**
	 * Timezones
	 * 时区列表 
	 * Returns an array of timezones. This is a helper function
	 * for various other ones in this library
	 * 返回一个数组的时区。这是其他的一个helper函数在这个库
	 * @param	string	timezone
	 * @return	string
	 */
	function timezones($tz = '')
	{
		// Note: Don't change the order of these even though 注意:不改变这些即使的顺序
		// some items appear to be in the wrong order 有些东西似乎错了订单

		$zones = array(
			'UM12'		=> -12,
			'UM11'		=> -11,
			'UM10'		=> -10,
			'UM95'		=> -9.5,
			'UM9'		=> -9,
			'UM8'		=> -8,
			'UM7'		=> -7,
			'UM6'		=> -6,
			'UM5'		=> -5,
			'UM45'		=> -4.5,
			'UM4'		=> -4,
			'UM35'		=> -3.5,
			'UM3'		=> -3,
			'UM2'		=> -2,
			'UM1'		=> -1,
			'UTC'		=> 0,
			'UP1'		=> +1,
			'UP2'		=> +2,
			'UP3'		=> +3,
			'UP35'		=> +3.5,
			'UP4'		=> +4,
			'UP45'		=> +4.5,
			'UP5'		=> +5,
			'UP55'		=> +5.5,
			'UP575'		=> +5.75,
			'UP6'		=> +6,
			'UP65'		=> +6.5,
			'UP7'		=> +7,
			'UP8'		=> +8,
			'UP875'		=> +8.75,
			'UP9'		=> +9,
			'UP95'		=> +9.5,
			'UP10'		=> +10,
			'UP105'		=> +10.5,
			'UP11'		=> +11,
			'UP115'		=> +11.5,
			'UP12'		=> +12,
			'UP1275'	=> +12.75,
			'UP13'		=> +13,
			'UP14'		=> +14
		);

		if ($tz === '')
		{
			return $zones;
		}

		return isset($zones[$tz]) ? $zones[$tz] : 0;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('date_range'))
{
	/**
	 * Date range
	 * 日期范围
	 * Returns a list of dates within a specified period.
	 * 指定的时间内返回一个列表的日期。
	 * @param	int	unix_start	UNIX timestamp of period start date UNIX时间戳的开始日期
	 * @param	int	unix_end|days	UNIX timestamp of period end date UNIX时间戳的结束日期
	 *					or interval in days. 或在天间隔。
	 * @param	mixed	is_unix		Specifies whether the second parameter 指定第二个参数
	 *					is a UNIX timestamp or a day interval 是一个UNIX时间戳或间隔一天吗
	 *					 - TRUE or 'unix' for a timestamp   真或unix是时间戳
	 *					 - FALSE or 'days' for an interval 假或days是间隔日
	 * @param	string  date_format	Output date format, same as in date() 输出日期格式,一样的日期()
	 * @return	array
	 */
	function date_range($unix_start = '', $mixed = '', $is_unix = TRUE, $format = 'Y-m-d')
	{
		if ($unix_start == '' OR $mixed == '' OR $format == '')
		{
			return FALSE;
		}

		$is_unix = ! ( ! $is_unix OR $is_unix === 'days');

		// Validate input and try strtotime() on invalid timestamps/intervals, just in case 对无效的时间戳验证输入和尝试strtotime()/间隔,以防
		if ( ( ! ctype_digit((string) $unix_start) && ($unix_start = @strtotime($unix_start)) === FALSE)
			OR ( ! ctype_digit((string) $mixed) && ($is_unix === FALSE OR ($mixed = @strtotime($mixed)) === FALSE))
			OR ($is_unix === TRUE && $mixed < $unix_start))
		{
			return FALSE;
		}

		if ($is_unix && ($unix_start == $mixed OR date($format, $unix_start) === date($format, $mixed)))
		{
			return array(date($format, $unix_start));
		}

		$range = array();

		/* NOTE: Even though the DateTime object has many useful features, it appears that
		 *	 it doesn't always handle properly timezones, when timestamps are passed
		 *	 directly to its constructor. Neither of the following gave proper results:
		 * 注意:尽管DateTime对象有许多有用的特性,它似乎并不总是正确处理时区,当时间戳直接传递给它的构造函数。没有以下给出正确的结果:
		 *		new DateTime('<timestamp>')
		 *		new DateTime('<timestamp>', '<timezone>')
		 *
		 *	 --- available in PHP 5.3: 可能在PHP5.3
		 *
		 *		DateTime::createFromFormat('<format>', '<timestamp>')
		 *		DateTime::createFromFormat('<format>', '<timestamp>', '<timezone')
		 *
		 *	 ... so we'll have to set the timestamp after the object is instantiated.
		 *	 Furthermore, in PHP 5.3 we can use DateTime::setTimestamp() to do that and
		 *	 given that we have UNIX timestamps - we should use it.
		 * 所以我们要设置对象被实例化后的时间戳。此外,在PHP 5.3我们可以使用DateTime::setTimestamp()这样做,因为我们有UNIX时间戳——我们应该使用它。
		*/
		$from = new DateTime();

		if (is_php('5.3'))
		{
			$from->setTimestamp($unix_start);
			if ($is_unix)
			{
				$arg = new DateTime();
				$arg->setTimestamp($mixed);
			}
			else
			{
				$arg = (int) $mixed;
			}

			$period = new DatePeriod($from, new DateInterval('P1D'), $arg);
			foreach ($period as $date)
			{
				$range[] = $date->format($format);
			}

			/* If a period end date was passed to the DatePeriod constructor, it might not
			 * be in our results. Not sure if this is a bug or it's just possible because
			 * the end date might actually be less than 24 hours away from the previously
			 * generated DateTime object, but either way - we have to append it manually.
			 * 如果一段时间结束日期是传递到DatePeriod构造函数,它可能不是在我们的结果。不知道这是一个错误还是只是因为结束日期可能会不到24小时从先前生成的DateTime对象,但不管怎样,我们必须手动添加。
			 */
			if ( ! is_int($arg) && $range[count($range) - 1] !== $arg->format($format))
			{
				$range[] = $arg->format($format);
			}

			return $range;
		}

		$from->setDate(date('Y', $unix_start), date('n', $unix_start), date('j', $unix_start));
		$from->setTime(date('G', $unix_start), date('i', $unix_start), date('s', $unix_start));
		if ($is_unix)
		{
			$arg = new DateTime();
			$arg->setDate(date('Y', $mixed), date('n', $mixed), date('j', $mixed));
			$arg->setTime(date('G', $mixed), date('i', $mixed), date('s', $mixed));
		}
		else
		{
			$arg = (int) $mixed;
		}
		$range[] = $from->format($format);

		if (is_int($arg)) // Day intervals 天的间隔
		{
			do
			{
				$from->modify('+1 day');
				$range[] = $from->format($format);
			}
			while (--$arg > 0);
		}
		else // end date UNIX timestamp 结束日期UNIX时间戳
		{
			for ($from->modify('+1 day'), $end_check = $arg->format('Ymd'); $from->format('Ymd') < $end_check; $from->modify('+1 day'))
			{
				$range[] = $from->format($format);
			}

			// Our loop only appended dates prior to our end date 我们的循环只附加日期之前我们的结束日期
			$range[] = $arg->format($format);
		}

		return $range;
	}
}
