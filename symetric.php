<?php
//gausseuler
declare(strict_types=1);

require('rijndael.php');
//require(rijndael_ext.php');
require('twofish.php');
require('serpent.php');

class symetric
{
	private $key;
	private $iv;
	private $algo;
	private $mode;

	public function ecbModeEnc()//electronic codebook
	{
		
	}

	public function cbcModeEnc()//cipher blockchain
	{
		
	}

	public function cfbModeEnc()//cipher feedback
	{
		
	}

	public function ofbModeEnc()//output feedback
	{
		
	}

	public function ctrModeEnc()//counter
	{

	}

	public function gcbModeEnc()//galois counter
	{

	}

	public function pcbModeEnc()//propagating blockchain
	{

	}

	public function ecbModeDec()//electronic codebook
	{

	}

	public function cbcModeDec()//cipher blockchain
	{

	}

	public function cfbModeDec()//cipher feedback
	{

	}

	public function ofbModeDec()//output feedback
	{

	}

	public function ctrModeDec()//counter
	{

	}

	public function gcbModeDec()//galois counter
	{

	}

	public function pcbModeDec()//propagating blockchain
	{

	}

	public function encrypt($mode, $plain, $key, $iv, $algo)
	{

	}

	public function decrypt($mode, $cipher, $key, $iv, $algo)
	{

	}

	public function serpent_dec(string $text, string $key)
	{
		//$cipher = new serpent();
		//$cipher->serset_key($key);
		//$cipher->serdecrypt($text);
		$code = openssl_decrypt($text, 'aes-256-ecb', $key);//, OPENSSL_RAW_DATA, $iv);

		//$code = mcrypt_decrypt(MCRYPT_SERPENT_256, $key, $text, MCRYPT_MODE_ECB);

		return $code;
	}

	function serpent_enc(string $text, string $key)
	{
		//$cipher = new serpent();
		//$cipher->serset_key($key);
		//$cipher->serencrypt($text);
		$code = openssl_encrypt($text, 'aes-256-ecb', $key);//, OPENSSL_RAW_DATA, $iv);

		//$code = mcrypt_encrypt(MCRYPT_SERPENT_256, $key, $text, MCRYPT_MODE_ECB);

		return $code;
	}
}
class TwofishInstance
{
	public $l_key;
	public $s_key = [];
	public $mk_tab = [];
	public $k_len;

public function __construct()
{
$this->l_key = [];
}
}

class rinjdael_encrypt_ctx
{
	public $ks = [];
	public $b = [];
	public $l;
}

function rinjdael_enc(string $text, string $key)
{
	//$cipher = new rinjdael_encrypt_ctx();
	//generar structura
	/*
		#define KS_LENGTH		60
	*/
	//$cipher->aesencrypt_key();
	//$cipher->aesencrypt();

	$code = openssl_encrypt($text, 'aes-256-ecb', $key);//, OPENSSL_RAW_DATA, $iv);

	//$code = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB);

	return $code;
}

function rinjdael_dec(string $text, string $key)
{
	//$cipher = new rinjdael_encrypt_ctx();
	//generar structura
	/*
	#define KS_LENGTH		60
	*/
	//$cipher->aesdecrypt_key();
	//$cipher->aesdecrypt();

	$code = openssl_decrypt($text, 'aes-256-ecb', $key);//, OPENSSL_RAW_DATA, $iv);

	//$code = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB);

	return $code;
}

function twofish_enc(string $text, string $key)
{
	//$instance = new TwofishInstance();
	//$cipher = new twofish();
	//$cipher->twoset_key($instance, $key, 4);
	//$code=$cipher->twofish_encrypt($instance, $text, $code);
	$code = openssl_encrypt($text, 'aes-256-ecb', $key);//, OPENSSL_RAW_DATA, $iv);

	//$code = mcrypt_encrypt(MCRYPT_TWOFISH_256, $key, $text, MCRYPT_MODE_ECB);

	return $code;
}

function twofish_dec(string $text, string $key)
{
	//$instance = new TwofishInstance();
	//$cipher = new twofish();
	//$cipher->twoset_key($instance, $key, 4);
	//$cipher->twofish_decrypt($instance, $text, $code);
	$code = openssl_decrypt($text, 'aes-256-ecb', $key);//, OPENSSL_RAW_DATA, $iv);

	//$code = mcrypt_decrypt(MCRYPT_TWOFISH_256, $key, $text, MCRYPT_MODE_ECB);

	return $code;
}
?>