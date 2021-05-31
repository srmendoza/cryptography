<?php
declare(strict_types=1);

class serpent
{
	/*
	#define beforeS0(f) f(0,a,b,c,d,e)
	#define afterS0(f) f(1,b,e,c,a,d)
	#define afterS1(f) f(2,c,b,a,e,d)
	#define afterS2(f) f(3,a,e,b,d,c)
	#define afterS3(f) f(4,e,b,d,c,a)
	#define afterS4(f) f(5,b,a,e,c,d)
	#define afterS5(f) f(6,a,c,b,e,d)
	#define afterS6(f) f(7,a,c,d,b,e)
	#define afterS7(f) f(8,d,e,b,a,c)

	// order of output from inverse S-box functions
	#define beforeI7(f) f(8,a,b,c,d,e)
	#define afterI7(f) f(7,d,a,b,e,c)
	#define afterI6(f) f(6,a,b,c,e,d)
	#define afterI5(f) f(5,b,d,e,c,a)
	#define afterI4(f) f(4,b,c,e,a,d)
	#define afterI3(f) f(3,a,b,e,c,d)
	#define afterI2(f) f(2,b,d,e,c,a)
	#define afterI1(f) f(1,a,b,c,e,d)
	#define afterI0(f) f(0,a,d,b,e,c)
	*/

	private function rotlFixed($x, $n)
	{
		return ((($x) << ($n)) | (($x) >> (32 - ($n))));
	}

	private function rotrFixed($x, $n)
	{
		return ((($x) >> ($n)) | (($x) << (32 - ($n))));
	}

	private function LT($i,&$a,&$b,&$c,&$d,&$e)
	{
		$a = $this->rotlFixed($a, 13);
		$c = $this->rotlFixed($c, 3);
		$d = $this->rotlFixed($d ^ $c ^ ($a << 3), 7);
		$b = $this->rotlFixed($b ^ $a ^ $c, 1);
		$a = $this->rotlFixed($a ^ $b ^ $d, 5);
		$c = $this->rotlFixed($c ^ $d ^ ($b << 7), 22);
	}

	private function ILT($i,&$a,&$b,&$c,&$d,&$e)
	{
		$c = $this->rotrFixed($c, 22);
		$a = $this->rotrFixed($a, 5);
		$c ^= $d ^ ($b << 7);
		$a ^= $b ^ $d;
		$b = $this->rotrFixed($b, 1);
		$d = $this->rotrFixed($d, 7) ^ $c ^ ($a << 3);
		$b ^= $a ^ $c;
		$c = $this->rotrFixed($c, 3);
		$a = $this->rotrFixed($a, 13);
	}

	private function S0($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r3 ^= $r0;
		$r4 = $r1;
		$r1 &= $r3;
		$r4 ^= $r2;
		$r1 ^= $r0;
		$r0 |= $r3;
		$r0 ^= $r4;
		$r4 ^= $r3;
		$r3 ^= $r2;
		$r2 |= $r1;
		$r2 ^= $r4;
		$r4 = ~$r4;
		$r4 |= $r1;
		$r1 ^= $r3;
		$r1 ^= $r4;
		$r3 |= $r0;
		$r1 ^= $r3;
		$r4 ^= $r3;
	}

	private function I0($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r2 = ~$r2;
		$r4 = $r1;
		$r1 |= $r0;
		$r4 = ~$r4;
		$r1 ^= $r2;
		$r2 |= $r4;
		$r1 ^= $r3;
		$r0 ^= $r4;
		$r2 ^= $r0;
		$r0 &= $r3;
		$r4 ^= $r0;
		$r0 |= $r1;
		$r0 ^= $r2;
		$r3 ^= $r4;
		$r2 ^= $r1;
		$r3 ^= $r0;
		$r3 ^= $r1;
		$r2 &= $r3;
		$r4 ^= $r2;
	}

	private function S1($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r0 = ~$r0;
		$r2 = ~$r2;
		$r4 = $r0;
		$r0 &= $r1;
		$r2 ^= $r0;
		$r0 |= $r3;
		$r3 ^= $r2;
		$r1 ^= $r0;
		$r0 ^= $r4;
		$r4 |= $r1;
		$r1 ^= $r3;
		$r2 |= $r0;
		$r2 &= $r4;
		$r0 ^= $r1;
		$r1 &= $r2;
		$r1 ^= $r0;
		$r0 &= $r2;
		$r0 ^= $r4;
	}

	private function I1($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r1;
		$r1 ^= $r3;
		$r3 &= $r1;
		$r4 ^= $r2;
		$r3 ^= $r0;
		$r0 |= $r1;
		$r2 ^= $r3;
		$r0 ^= $r4;
		$r0 |= $r2;
		$r1 ^= $r3;
		$r0 ^= $r1;
		$r1 |= $r3;
		$r1 ^= $r0;
		$r4 = ~$r4;
		$r4 ^= $r1;
		$r1 |= $r0;
		$r1 ^= $r0;
		$r1 |= $r4;
		$r3 ^= $r1;
	}

	private function S2($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r0;
		$r0 &= $r2;
		$r0 ^= $r3;
		$r2 ^= $r1;
		$r2 ^= $r0;
		$r3 |= $r4;
		$r3 ^= $r1;
		$r4 ^= $r2;
		$r1 = $r3;
		$r3 |= $r4;
		$r3 ^= $r0;
		$r0 &= $r1;
		$r4 ^= $r0;
		$r1 ^= $r3;
		$r1 ^= $r4;
		$r4 = ~$r4;
	}

	private function I2($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r2 ^= $r3;
		$r3 ^= $r0;
		$r4 = $r3;
		$r3 &= $r2;
		$r3 ^= $r1;
		$r1 |= $r2;
		$r1 ^= $r4;
		$r4 &= $r3;
		$r2 ^= $r3;
		$r4 &= $r0;
		$r4 ^= $r2;
		$r2 &= $r1;
		$r2 |= $r0;
		$r3 = ~$r3;
		$r2 ^= $r3;
		$r0 ^= $r3;
		$r0 &= $r1;
		$r3 ^= $r4;
		$r3 ^= $r0;
	}

	private function S3($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r0;
		$r0 |= $r3;
		$r3 ^= $r1;
		$r1 &= $r4;
		$r4 ^= $r2;
		$r2 ^= $r3;
		$r3 &= $r0;
		$r4 |= $r1;
		$r3 ^= $r4;
		$r0 ^= $r1;
		$r4 &= $r0;
		$r1 ^= $r3;
		$r4 ^= $r2;
		$r1 |= $r0;
		$r1 ^= $r2;
		$r0 ^= $r3;
		$r2 = $r1;
		$r1 |= $r3;
		$r1 ^= $r0;
	}

	private function I3($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r2;
		$r2 ^= $r1;
		$r1 &= $r2;
		$r1 ^= $r0;
		$r0 &= $r4;
		$r4 ^= $r3;
		$r3 |= $r1;
		$r3 ^= $r2;
		$r0 ^= $r4;
		$r2 ^= $r0;
		$r0 |= $r3;
		$r0 ^= $r1;
		$r4 ^= $r2;
		$r2 &= $r3;
		$r1 |= $r3;
		$r1 ^= $r2;
		$r4 ^= $r0;
		$r2 ^= $r4;
	}

	private function S4($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r1 ^= $r3;
		$r3 = ~$r3;
		$r2 ^= $r3;
		$r3 ^= $r0;
		$r4 = $r1;
		$r1 &= $r3;
		$r1 ^= $r2;
		$r4 ^= $r3;
		$r0 ^= $r4;
		$r2 &= $r4;
		$r2 ^= $r0;
		$r0 &= $r1;
		$r3 ^= $r0;
		$r4 |= $r1;
		$r4 ^= $r0;
		$r0 |= $r3;
		$r0 ^= $r2;
		$r2 &= $r3;
		$r0 = ~$r0;
		$r4 ^= $r2;
	}

	private function I4($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r2;
		$r2 &= $r3;
		$r2 ^= $r1;
		$r1 |= $r3;
		$r1 &= $r0;
		$r4 ^= $r2;
		$r4 ^= $r1;
		$r1 &= $r2;
		$r0 = ~$r0;
		$r3 ^= $r4;
		$r1 ^= $r3;
		$r3 &= $r0;
		$r3 ^= $r2;
		$r0 ^= $r1;
		$r2 &= $r0;
		$r3 ^= $r0;
		$r2 ^= $r4;
		$r2 |= $r3;
		$r3 ^= $r0;
		$r2 ^= $r1;
	}

	private function S5($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r0 ^= $r1;
		$r1 ^= $r3;
		$r3 = ~$r3;
		$r4 = $r1;
		$r1 &= $r0;
		$r2 ^= $r3;
		$r1 ^= $r2;
		$r2 |= $r4;
		$r4 ^= $r3;
		$r3 &= $r1;
		$r3 ^= $r0;
		$r4 ^= $r1;
		$r4 ^= $r2;
		$r2 ^= $r0;
		$r0 &= $r3;
		$r2 = ~$r2;
		$r0 ^= $r4;
		$r4 |= $r3;
		$r2 ^= $r4;
	}

	private function I5($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r1 = ~$r1;
		$r4 = $r3;
		$r2 ^= $r1;
		$r3 |= $r0;
		$r3 ^= $r2;
		$r2 |= $r1;
		$r2 &= $r0;
		$r4 ^= $r3;
		$r2 ^= $r4;
		$r4 |= $r0;
		$r4 ^= $r1;
		$r1 &= $r2;
		$r1 ^= $r3;
		$r4 ^= $r2;
		$r3 &= $r4;
		$r4 ^= $r1;
		$r3 ^= $r0;
		$r3 ^= $r4;
		$r4 = ~$r4;
	}

	private function S6($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r2 = ~$r2;
		$r4 = $r3;
		$r3 &= $r0;
		$r0 ^= $r4;
		$r3 ^= $r2;
		$r2 |= $r4;
		$r1 ^= $r3;
		$r2 ^= $r0;
		$r0 |= $r1;
		$r2 ^= $r1;
		$r4 ^= $r0;
		$r0 |= $r3;
		$r0 ^= $r2;
		$r4 ^= $r3;
		$r4 ^= $r0;
		$r3 = ~$r3;
		$r2 &= $r4;
		$r2 ^= $r3;
	}

	private function I6($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r0 ^= $r2;
		$r4 = $r2;
		$r2 &= $r0;
		$r4 ^= $r3;
		$r2 = ~$r2;
		$r3 ^= $r1;
		$r2 ^= $r3;
		$r4 |= $r0;
		$r0 ^= $r2;
		$r3 ^= $r4;
		$r4 ^= $r1;
		$r1 &= $r3;
		$r1 ^= $r0;
		$r0 ^= $r3;
		$r0 |= $r2;
		$r3 ^= $r1;
		$r4 ^= $r0;
	}

	private function S7($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r2;
		$r2 &= $r1;
		$r2 ^= $r3;
		$r3 &= $r1;
		$r4 ^= $r2;
		$r2 ^= $r1;
		$r1 ^= $r0;
		$r0 |= $r4;
		$r0 ^= $r2;
		$r3 ^= $r1;
		$r2 ^= $r3;
		$r3 &= $r0;
		$r3 ^= $r4;
		$r4 ^= $r2;
		$r2 &= $r0;
		$r4 = ~$r4;
		$r2 ^= $r4;
		$r4 &= $r0;
		$r1 ^= $r3;
		$r4 ^= $r1;
	}

	private function I7($i, &$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r2;
		$r2 ^= $r0;
		$r0 &= $r3;
		$r2 = ~$r2;
		$r4 |= $r3;
		$r3 ^= $r1;
		$r1 |= $r0;
		$r0 ^= $r2;
		$r2 &= $r4;
		$r1 ^= $r2;
		$r2 ^= $r0;
		$r0 |= $r2;
		$r3 &= $r4;
		$r0 ^= $r3;
		$r4 ^= $r1;
		$r3 ^= $r4;
		$r4 |= $r0;
		$r3 ^= $r2;
		$r4 ^= $r2;
	}

	private function KX($r, &$a, &$b, &$c, &$d, &$e)
	{
		$a ^= $k[4 * $r + 0];
		$b ^= $k[4 * $r + 1];
		$c ^= $k[4 * $r + 2];
		$d ^= $k[4 * $r + 3];
	}

	private function S0f(&$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r3 ^= $r0;
		$r4 = $r1;
		$r1 &= $r3;
		$r4 ^= $r2;
		$r1 ^= $r0;
		$r0 |= $r3;
		$r0 ^= $r4;
		$r4 ^= $r3;
		$r3 ^= $r2;
		$r2 |= $r1;
		$r2 ^= $r4;
		$r4 = ~$r4;
		$r4 |= $r1;
		$r1 ^= $r3;
		$r1 ^= $r4;
		$r3 |= $r0;
		$r1 ^= $r3;
		$r4 ^= $r3;
	}

	private function S1f(&$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r0 = ~$r0;
		$r2 = ~$r2;
		$r4 = $r0;
		$r0 &= $r1;
		$r2 ^= $r0;
		$r0 |= $r3;
		$r3 ^= $r2;
		$r1 ^= $r0;
		$r0 ^= $r4;
		$r4 |= $r1;
		$r1 ^= $r3;
		$r2 |= $r0;
		$r2 &= $r4;
		$r0 ^= $r1;
		$r1 &= $r2;
		$r1 ^= $r0;
		$r0 &= $r2;
		$r0 ^= $r4;
	}

	private function S2f(&$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r0;
		$r0 &= $r2;
		$r0 ^= $r3;
		$r2 ^= $r1;
		$r2 ^= $r0;
		$r3 |= $r4;
		$r3 ^= $r1;
		$r4 ^= $r2;
		$r1 = $r3;
		$r3 |= $r4;
		$r3 ^= $r0;
		$r0 &= $r1;
		$r4 ^= $r0;
		$r1 ^= $r3;
		$r1 ^= $r4;
		$r4 = ~$r4;
	}

	private function S3f(&$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r0;
		$r0 |= $r3;
		$r3 ^= $r1;
		$r1 &= $r4;
		$r4 ^= $r2;
		$r2 ^= $r3;
		$r3 &= $r0;
		$r4 |= $r1;
		$r3 ^= $r4;
		$r0 ^= $r1;
		$r4 &= $r0;
		$r1 ^= $r3;
		$r4 ^= $r2;
		$r1 |= $r0;
		$r1 ^= $r2;
		$r0 ^= $r3;
		$r2 = $r1;
		$r1 |= $r3;
		$r1 ^= $r0;
	}

	private function S4f(&$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r1 ^= $r3;
		$r3 = ~$r3;
		$r2 ^= $r3;
		$r3 ^= $r0;
		$r4 = $r1;
		$r1 &= $r3;
		$r1 ^= $r2;
		$r4 ^= $r3;
		$r0 ^= $r4;
		$r2 &= $r4;
		$r2 ^= $r0;
		$r0 &= $r1;
		$r3 ^= $r0;
		$r4 |= $r1;
		$r4 ^= $r0;
		$r0 |= $r3;
		$r0 ^= $r2;
		$r2 &= $r3;
		$r0 = ~$r0;
		$r4 ^= $r2;
	}

	private function S5f(&$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r0 ^= $r1;
		$r1 ^= $r3;
		$r3 = ~$r3;
		$r4 = $r1;
		$r1 &= $r0;
		$r2 ^= $r3;
		$r1 ^= $r2;
		$r2 |= $r4;
		$r4 ^= $r3;
		$r3 &= $r1;
		$r3 ^= $r0;
		$r4 ^= $r1;
		$r4 ^= $r2;
		$r2 ^= $r0;
		$r0 &= $r3;
		$r2 = ~$r2;
		$r0 ^= $r4;
		$r4 |= $r3;
		$r2 ^= $r4;
	}

	private function S6f(&$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r2 = ~$r2;
		$r4 = $r3;
		$r3 &= $r0;
		$r0 ^= $r4;
		$r3 ^= $r2;
		$r2 |= $r4;
		$r1 ^= $r3;
		$r2 ^= $r0;
		$r0 |= $r1;
		$r2 ^= $r1;
		$r4 ^= $r0;
		$r0 |= $r3;
		$r0 ^= $r2;
		$r4 ^= $r3;
		$r4 ^= $r0;
		$r3 = ~$r3;
		$r2 &= $r4;
		$r2 ^= $r3;
	}

	private function S7f(&$r0, &$r1, &$r2, &$r3, &$r4)
	{
		$r4 = $r2;
		$r2 &= $r1;
		$r2 ^= $r3;
		$r3 &= $r1;
		$r4 ^= $r2;
		$r2 ^= $r1;
		$r1 ^= $r0;
		$r0 |= $r4;
		$r0 ^= $r2;
		$r3 ^= $r1;
		$r2 ^= $r3;
		$r3 &= $r0;
		$r3 ^= $r4;
		$r4 ^= $r2;
		$r2 &= $r0;
		$r4 = ~$r4;
		$r2 ^= $r4;
		$r4 &= $r0;
		$r1 ^= $r3;
		$r4 ^= $r1;
	}

	private function KXf(&$k, $r, &$a, &$b, &$c, &$d)
	{
		$a ^= $k[$r];
		$b ^= $k[$r + 1];
		$c ^= $k[$r + 2];
		$d ^= $k[$r + 3];
	}

	private function LK($r, &$a, &$b, &$c, &$d, &$e)
	{
		$a = $k[(8-$r)*4 + 0];
		$b = $k[(8-$r)*4 + 1];
		$c = $k[(8-$r)*4 + 2];
		$d = $k[(8-$r)*4 + 3];
	}

	private function SK($r, $a, $b, $c, $d, $e)
	{
		$k[(8-$r)*4 + 4] = $a;
		$k[(8-$r)*4 + 5] = $b;
		$k[(8-$r)*4 + 6] = $c;
		$k[(8-$r)*4 + 7] = $d;
	}

	private function LKf($k, $r, &$a, &$b, &$c, &$d)
	{
		$a = $k[$r];
		$b = $k[$r + 1];
		$c = $k[$r + 2];
		$d = $k[$r + 3];
	}

	private function SKf(&$k, $r, $a, $b, $c, $d)
	{
		$k[$r + 4] = $a;
		$k[$r + 5] = $b;
		$k[$r + 6] = $c;
		$k[$r + 7] = $d;
	}

	private function LTf(&$a, &$b, &$c, &$d)
	{
		$a = $this->rotlFixed($a, 13);
		$c = $this->rotlFixed($c, 3);
		$d = $this->rotlFixed($d ^ $c ^ ($a << 3), 7);
		$b = $this->rotlFixed($b ^ $a ^ $c, 1);
		$a = $this->rotlFixed($a ^ $b ^ $d, 5);
		$c = $this->rotlFixed($c ^ $d ^ ($b << 7), 22);
	}

	private function ILTf(&$a, &$b, &$c, &$d)
	{
		$c = $this->rotrFixed($c, 22);
		$a = $this->rotrFixed($a, 5);
		$c ^= $d ^ ($b << 7);
		$a ^= $b ^ $d;
		$b = $this->rotrFixed($b, 1);
		$d = $this->rotrFixed($d, 7) ^ $c ^ ($a << 3);
		$b ^= $a ^ $c;
		$c = $this->rotrFixed($c, 3);
		$a = $this->rotrFixed($a, 13);
	}

	public function serset_key(&$userKey, $keylen, $ks)
	{
		$a; $b; $c; $d; $e;
		$k=&$ks;
		$t;
		$i;

		for($i=0; $i <$keylen/32; $i++)
		{
			$k[$i]=$userKey[$i];
		}

		if($keylen < 32)
		{
			$k[$keylen/4] |= 1 << ($keylen%4)*8;
		}
		$k=$k+8;//revisar
		$t=$k[-1];
		for($i=0; $i<132; $i++)
		{
			$k[$i] = $t = $this->rotlFixed($k[$i-8] ^ $k[$i-5] ^ $k[$i-3] ^ $t ^ 0x9e3779b9 ^ $i, 11);
		}
		$k=$k-20;//revisar
		for($i=0; $i<4; $i++)
		{
			$this->LKf($k, 20, $a, $e, $b, $d); 
			$this->S3f($a, $e, $b, $d, $c); 
			$this->SKf($k, 16, $e, $b, $d, $c);
			$this->LKf($k, 24, $c, $b, $a, $e); 
			$this->S2f($c, $b, $a, $e, $d); 
			$this->SKf($k, 20, $a, $e, $b, $d);
			$this->LKf($k, 28, $b, $e, $c, $a); 
			$this->S1f($b, $e, $c, $a, $d); 
			$this->SKf($k, 24, $c, $b, $a, $e);
			$this->LKf($k, 32, $a, $b, $c, $d); 
			$this->S0f($a, $b, $c, $d, $e); 
			$this->SKf($k, 28, $b, $e, $c, $a);
			$k =$k + 8*4;//revisar
			$this->LKf($k, 4, $a, $c, $d, $b); 
			$this->S7f($a, $c, $d, $b, $e); 
			$this->SKf($k,  0, $d, $e, $b, $a);
			$this->LKf($k,  8, $a, $c, $b, $e); 
			$this->S6f($a, $c, $b, $e, $d); 
			$this->SKf($k,  4, $a, $c, $d, $b);
			$this->LKf($k, 12, $b, $a, $e, $c); 
			$this->S5f($b, $a, $e, $c, $d); 
			$this->SKf($k,  8, $a, $c, $b, $e);
			$this->LKf($k, 16, $e, $b, $d, $c); 
			$this->S4f($e, $b, $d, $c, $a); 
			$this->SKf($k, 12, $b, $a, $e, $c);
		}
		$this->LKf($k, 20, $a, $e, $b, $d); 
		$this->S3f($a, $e, $b, $d, $c); 
		$this->SKf($k, 16, $e, $b, $d, $c);
	}

	public function serencrypt(&$inBlock, &$outBlock, $ks)
	{
		$a; $b; $c; $d; $e; $i=1;
		$k=&$ks;
		$in=&$inBlock;
		$out=&$outBlock;
		do{
			$this->KXf($k,  0, $a, $b, $c, $d); 
			$this->S0f($a, $b, $c, $d, $e);
			$this->LTf($b, $e, $c, $a);
			$this->KXf($k,  4, $b, $e, $c, $a); 
			$this->S1f($b, $e, $c, $a, $d);
			$this->LTf($c, $b, $a, $e);
			$this->KXf($k,  8, $c, $b, $a, $e); 
			$this->S2f($c, $b, $a, $e, $d);
			$this->LTf($a, $e, $b, $d);
			$this->KXf($k, 12, $a, $e, $b, $d); 
			$this->S3f($a, $e, $b, $d, $c);
			$this->LTf($e, $b, $d, $c);
			$this->KXf($k, 16, $e, $b, $d, $c); 
			$this->S4f($e, $b, $d, $c, $a);
			$this->LTf($b, $a, $e, $c);
			$this->KXf($k, 20, $b, $a, $e, $c);
			$this->S5f($b, $a, $e, $c, $d);
			$this->LTf($a, $c, $b, $e);
			$this->KXf($k, 24, $a, $c, $b, $e);
			$this->S6f($a, $c, $b, $e, $d);
			$this->LTf($a, $c, $d, $b);
			$this->KXf($k, 28, $a, $c, $d, $b);
			$this->S7f($a, $c, $d, $b, $e);
			if($i === 4)
			{
				break;//revisar
			}
			++$i;
			$c = $b;
			$b = $e;
			$e = $d;
			$d = $a;
			$a = $e;
			$k+=32;
			$this->LTf($a, $b, $c, $d);
		}while(1);//revisar
		$this->KXf($k, 32, $d, $e, $b, $a);

		$out[0] = $d;
		$out[1] = $e;
		$out[2] = $b;
		$out[3] = $a;
	}

	public function serdecrypt(&$inBlock, &$outBlock, $ks)
	{
		$i=4;
		$k = &$ks;
		$in = &$inBlock;
		$out = &$outBlock;
		$a = $in[0];
		$b = $in[1];
		$c = $in[2];
		$d = $in[3];
		$this->KXf($k, 32, $a, $b, $c, $d);
		//goto start;//revisar
		do
		{
			$c = $b;
			$b = $d;
			$d = $e;
			$k -= 32;
			$this->ILT(8, $a, $b, $c, $d, $e);
			start: //revisar
			$this->I7(8, $a, $b, $c, $d, $e);
			$this->KXf($k, 28, $d, $a, $b, $e);
			$this->ILTf($d, $a, $b, $e);
			$this->I6(7, $d, $a, $b, $e, $c);
			$this->KXf($k, 24, $a, $b, $c, $e);
			$this->ILTf($a, $b, $c, $e);
			$this->I5(6, $a, $b, $c, $e, $d);
			$this->KXf($k, 20, $b, $d, $e, $c);
			$this->ILTf($b, $d, $e, $c);
			$this->I4(5, $b, $d, $e, $c, $a);
			$this->KXf($k, 16, $b, $c, $e, $a);
			$this->ILTf($b, $c, $e, $a);
			$this->I3(4, $b, $c, $e, $a, $d);
			$this->KXf($k, 12, $a, $b, $e, $c);
			$this->ILTf($a, $b, $e, $c);
			$this->I2(3, $a, $b, $e, $c, $d);
			$this->KXf($k, 8,  $b, $d, $e, $c);
			$this->ILTf($b, $d, $e, $c);
			$this->I1(2, $b, $d, $e, $c, $a);
			$this->KXf($k, 4,  $a, $b, $c, $e);
			$this->ILTf($a, $b, $c, $e);
			$this->I0(1, $a, $b, $c, $e, $d);
			$this->KXf($k, 0,  $a, $d, $b, $e);
		}while(--$i !=0);
		$out[0] = $a;
		$out[1] = $d;
		$out[2] = $b;
		$out[3] = $e;
	}
}
?>