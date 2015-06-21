<?php
namespace HTTP\Response;

class Language extends Header {

	const ARABIC = 'ar';
	const ARMENIAN = 'hy';
	const BENGALI = 'bn';
	const CHINESE = 'zh';
	const DANISH = 'da';
	const DUTCH = 'nl';
	const ENGLISH = 'en';
	const ENGLISH_US = 'en-US';
	const FRENCH = 'fr';
	const GERMAN = 'de';
	const HINDI = 'hi';
	const INDONESIAN = 'in';
	const JAPANESE = 'ja';
	const ITALIAN = 'it';
	const KOREAN = 'kr';
	const SPANISH = 'es';
	const PORTUGUESE = 'pt';
	const ROMANIAN = 'ro';
	const RUSSIAN = 'ru';
	const TAGALOG = 'tl';
	const TURKISH = 'tr';
	const VIETNAMESE = 'vi';

	public function getName() {
		return 'Language';
	}

	protected function setDefaults() {
		$this->values[] = self::ENGLISH;
	}
}
