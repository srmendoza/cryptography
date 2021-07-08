<?php
//gausseuler
declare(strict_types=1);

require('whirlpool.php');
require('sha2.php');
require('cubehash.php');

class hash_function
{
	private $algo;
	private $size;

	public function cubehash(string $text)
	{
		$code = $text;
		return $code;
	}

	public function sha2(string $text)
	{
		$code = "";
		//$ctx = new sha512_ctx();
		//generar structura
		/*
		#define SHA512_DIGEST_SIZE  64
		#define SHA512_BLOCK_SIZE  128
		*/
		//$cipher = new sha2();
		//$cipher->sha512_compile($ctx);
		//$cipher->sha512_begin($ctx);
		//$cipher->sha512_hash($data, $len, $ctx);
		//$cipher->sha512_end($hval, $ctx);
		//$cipher->sha512($hval, $ctx, $len);
		$code = hash('sha2', $text);
		return $code;
	}
}

class NESSIEstruct
{
	public $bitLength = [];
	public $buffer = [];
	public $bufferBits;
	public $bufferPos;
	public $hash = [];
}

class sha512_ctx
{
	public $count = [];
	public $hash = [];
	public $wbuf = [];

	public function __construct()
	{

	}
}

function whirlpool(string $text)
{
	$code = "";
	//$cipher = new whirlpool();
	//$structpointer = new NESSIEstruct();

	/*
	#define DIGESTBYTES 64
	#define DIGESTBITS  (8*DIGESTBYTES) /* 512 

	#define WBLOCKBYTES 64
	#define WBLOCKBITS  (8*WBLOCKBYTES) /* 512 

	#define LENGTHBYTES 32
	#define LENGTHBITS  (8*LENGTHBYTES) /* 256 
	*/
	//$cipher->whiinit($structpointer);
	//$cipher->whiadd($text, 48, $structpointer);
	//$cipher->whifinalize($structpointer, $code);
	$code = hash('whirlpool', $text);
	return $code;
}
?>