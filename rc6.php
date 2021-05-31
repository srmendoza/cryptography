<?php
declare(strict_types=1);

class rc6
{
	private function rotl($x, $y)
	{
		return $x;
	}

	private function rotr($x, $y)
	{
		return $x;
	}

	private function f_rnd($i, $a, $b, $c, $d)
	{
		$u = $this->rotl($d * ($d + $d + 1), 5);
		$t = $this->rotl($b * ($b + $b + 1), 5);
		$a = $this->rotl($a ^ $t, $u) + $this->l_key[$i];
		$c = $this->rotl($c ^ $u, $t) + $this->l_key[$i + 1];
	}

	private function i_rnd($i, $a, $b, $c, $d)
	{
		$u = $this->rotl($d * ($d + $d + 1), 5);
		$t = $this->rotl($b * ($b + $b + 1), 5);
		$c = $this->rotr($c - $this->l_key[$i + 1], $t) ^ $u;
		$a = $this->rotr($a - $this->l_key[$i], $u) ^ $t;
	}

	private $l_key=[];//storage for the key schedule

	public function set_key(&$in_key, &$key_len)
	{
		$i; $j; $k; $a; $b; $l=[]; $t;

		$this->l_key[0] = 0xb7e15163;
		for($k = 1; $k < 44; ++$k)
		{
			$this->l_key[$k] = $this->l_key[$k - 1] + 0x9e3779b9;
		}

		for($k = 0; $k < $key_len / 32; ++$k)
		{
			$l[$k] = $in_key[$k];
		}

		$t = ($key_len / 32) - 1; // $t = ($key_len / 32);
		$a = $b = $i = $j = 0;

		for($k = 0; $k < 132; ++$k)
		{
			$a = $this->rotl($l_key[$i] + $a + $b, 3); $b += $a;
			$b = $this->rotl($l[$j] + $b, $b);
			$this->l_key[$i] = $a; $l[$j] = $b;
			$i = ($i === 43 ? 0 : $i + 1); // $i = ($i + 1) % 44;
			$j = ($j === $t ? 0 : $j + 1);// $j = ($j + 1) % $t;
		}
		return $this->l_key;
	}

	/* encrypt a block of text*/

	public function encrypt(&$in_blk, &$out_blk)
	{
		$a;$b;$c;$d;$t;$u;

		$a = $in_blk[0];
		$b = $in_blk[1] + $this->l_key[0];
		$c = $in_blk[2];
		$d = $in_blk[3] + $this->l_key[1];

		$this->f_rnd( 2, $a, $b, $c, $d);
		$this->f_rnd( 4, $b, $c, $d, $a);
		$this->f_rnd( 6, $c, $d, $a, $b);
		$this->f_rnd( 8, $d, $a, $b, $c);
		$this->f_rnd(10, $a, $b, $c, $d);
		$this->f_rnd(12, $b, $c, $d, $a);
		$this->f_rnd(14, $c, $d, $a, $b);
		$this->f_rnd(16, $d, $a, $b, $c);
		$this->f_rnd(18, $a, $b, $c, $d);
		$this->f_rnd(20, $b, $c, $d, $a);
		$this->f_rnd(22, $c, $d, $a, $b);
		$this->f_rnd(24, $d, $a, $b, $c);
		$this->f_rnd(26, $a, $b, $c, $d);
		$this->f_rnd(28, $b, $c, $d, $a);
		$this->f_rnd(30, $c, $d, $a, $b);
		$this->f_rnd(32, $d, $a, $b, $c);
		$this->f_rnd(34, $a, $b, $c, $d);
		$this->f_rnd(36, $b, $c, $d, $a);
		$this->f_rnd(38, $c, $d, $a, $b);
		$this->f_rnd(40, $d, $a, $b, $c);

		$out_blk[0] = $a + $this->l_key[42];
		$out_blk[1] = $b;
		$out_blk[2] = $c + $this->l_key[43];
		$out_blk[3] = $d;
	}

	/* decrypt a block of text*/

	public function decrypt(&$in_blk, &$out_blk)
	{
		$a;$b;$c;$d;$t;$u;

		$d = $in_blk[3];
		$c = $in_blk[2] - $this->l_key[43];
		$b = $in_blk[1];
		$a = $in_blk[0] - $this->l_key[42];

		$this->i_rnd(40, $d, $a, $b, $c);
		$this->i_rnd(38, $c, $d, $a, $b);
		$this->i_rnd(36, $b, $c, $d, $a);
		$this->i_rnd(34, $a, $b, $c, $d);
		$this->i_rnd(32, $d, $a, $b, $c);
		$this->i_rnd(30, $c, $d, $a, $b);
		$this->i_rnd(28, $b, $c, $d, $a);
		$this->i_rnd(26, $a, $b, $c, $d);
		$this->i_rnd(24, $d, $a, $b, $c);
		$this->i_rnd(22, $c, $d, $a, $b);
		$this->i_rnd(20, $b, $c, $d, $a);
		$this->i_rnd(18, $a, $b, $c, $d);
		$this->i_rnd(16, $d, $a, $b, $c);
		$this->i_rnd(14, $c, $d, $a, $b);
		$this->i_rnd(12, $b, $c, $d, $a);
		$this->i_rnd(10, $a, $b, $c, $d);
		$this->i_rnd( 8, $d, $a, $b, $c);
		$this->i_rnd( 6, $c, $d, $a, $b);
		$this->i_rnd( 4, $b, $c, $d, $a);
		$this->i_rnd( 2, $a, $b, $c, $d);
		$out_blk[3] = $d - $this->l_key[1];
		$out_blk[2] = $c;
		$out_blk[1] = $b - $this->l_key[0];
		$out_blk[0] = $a;
	}
}
?>