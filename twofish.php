<?php
declare(strict_types=1);

class twofish
{
	private $ror4 = [0, 8, 1, 9, 2, 10, 3, 11, 4, 12, 5, 13, 6, 14, 7, 15];
	private $ashx = [0, 9, 2, 11, 4, 13, 6, 15, 8, 1, 10, 3, 12, 5, 14, 7];
	private $qt0 = [[8, 1, 7, 13, 6, 15, 3, 2, 0, 11, 5, 9, 14, 12, 10, 4],[2, 8, 11, 13, 15, 7, 6, 14, 3, 1, 9, 4, 0, 10, 12, 5]];
	private $qt1 = [[14, 12, 11, 8, 1, 2, 3, 5, 15, 4, 10, 6, 7, 0, 9, 13],[1, 14, 2, 11, 4, 12, 3, 7, 6, 13, 10, 5, 15, 9, 0, 8]];
	private $qt2 = [[11, 10, 5, 14, 6, 13, 9, 0, 12, 8, 15, 3, 2, 4, 7, 1],[4, 12, 7, 5, 1, 6, 9, 10, 0, 14, 13, 8, 2, 11, 3, 15]];
	private $qt3 = [[13, 7, 15, 4, 1, 2, 6, 14, 9, 11, 3, 0, 8, 5, 12, 10],[11, 9, 5, 1, 12, 3, 13, 14, 6, 4, 7, 15, 2, 0, 8, 10]];
	private $qt_gen = 0;
	private $q_tab = [];
	private $mt_gen = 0;
	private $G_M = 0x0169;
	private $m_tab = [];
	private $tab_5b = [ 0, (0x0169 >> 2), (0x0169 >> 1), ((0x0169 >> 1) ^ (0x0169 >> 2)) ];
	private $tab_ef = [ 0, ((0x0169 >> 1) ^ (0x0169 >> 2)), (0x0169 >> 1), (0x0169 >> 2) ];
	private $G_MOD = 0x0000014d;
	private $sb = [];

	private function SWAP_32($x)
	{
		return (($x << 24) | (($x & 0xff00) << 8) | (($x & 0x00ff0000) >> 8) | ($x >> 24));
	}

	private function ffm_01($x)
	{
		return ($x);
	}

	private function ffm_5b($x)
	{
		return (($x) ^ (($x) >> 2) ^ $this->tab_5b[($x) & 3]);
	}

	private function ffm_ef($x)
	{
		return (($x) ^ (($x) >> 1) ^ (($x) >> 2) ^ $this->tab_ef[($x) & 3]);
	}

	private function extract_byte($x, $n)
	{
		return (($x) >> (8 * $n));
	}

	private function rotr($x, $n)
	{
		return ((($x)>>($n))|(($x)<<(32-($n))));
	}

	private function rotl($x, $n)
	{
		return ((($x)<<($n))|(($x)>>(32-($n))));
	}

	private function q20($x)
	{
		return $this->q_tab[0][$this->q_tab[0][$x] ^ $this->extract_byte($key[1],0)] ^ $this->extract_byte($key[0],0);
	}

	private function q21($x)
	{
		return $this->q_tab[0][$this->q_tab[1][$x] ^ $this->extract_byte($key[1],1)] ^ $this->extract_byte($key[0],1);
	}

	private function q22($x)
	{
		return $this->q_tab[1][$this->q_tab[0][$x] ^ $this->extract_byte($key[1],2)] ^ $this->extract_byte($key[0],2);
	}

	private function q23($x)
	{
		return $this->q_tab[1][$this->q_tab[1][$x] ^ $this->extract_byte($key[1],3)] ^ $this->extract_byte($key[0],3);
	}

	private function q30($x)
	{
		return $this->q_tab[0][$this->q_tab[0][$this->q_tab[1][$x] ^ $this->extract_byte($key[2],0)] ^ 
		$this->extract_byte($key[1],0)] ^ $this->extract_byte($key[0],0);
	}

	private function q31($x)
	{
		return $this->q_tabtab[0][$this->q_tab[1][$this->q_tab[1][$x] ^ $this->extract_byte($key[2],1)] ^ 
		$this->extract_byte($key[1],1)] ^ $this->extract_byte($key[0],1);
	}

	private function q32($x)
	{
		return $this->q_tab[1][$this->q_tab[0][$this->q_tab[0][$x] ^ $this->extract_byte($key[2],2)] ^ 
		$this->extract_byte($key[1],2)] ^ $this->extract_byte($key[0],2);
	}

	private function q33($x)
	{
		return $this->q_tab[1][$this->q_tab[1][$this->q_tab[0][$x] ^ $this->extract_byte($key[2],3)] ^ 
		$this->extract_byte($key[1],3)] ^ $this->extract_byte($key[0],3);
	}

	private function q40($x)
	{
		return $this->q_tab[0][$this->q_tab[0][$this->q_tab[1][$this->q_tab[1][$x] ^ $this->extract_byte($key[3],0)] ^ 
		$this->extract_byte($key[2],0)] ^ $this->extract_byte($key[1],0)] ^ $this->extract_byte($key[0],0);
	}

	private function q41($x)
	{
		return $this->q_tab[0][$this->q_tab[1][$this->q_tab[1][$this->q_tab[0][$x] ^ $this->extract_byte($key[3],1)] ^ 
		$this->extract_byte($key[2],1)] ^ $this->extract_byte($key[1],1)] ^ $this->extract_byte($key[0],1);
	}

	private function q42($x)
	{
		return $this->q_tab[1][$this->q_tab[0][$this->q_tab[0][$this->q_tab[0][$x] ^ $this->extract_byte($key[3],2)] ^ 
		$this->extract_byte($key[2],2)] ^ $this->extract_byte($key[1],2)] ^ $this->extract_byte($key[0],2);
	}

	private function q43($x)
	{
		return $this->q_tab[1][$this->q_tab[1][$this->q_tab[0][$this->q_tab[1][$x] ^ $this->extract_byte($key[3],3)] ^ 
		$this->extract_byte($key[2],3)] ^ $this->extract_byte($key[1],3)] ^ $this->extract_byte($key[0],3);
	}

	private function qp($n, &$x)
	{
		$a0 = $x >> 4;
		$b0 = $x & 15;
		$a1 = $a0 ^ $b0;
		$b1 = $this->ror4[$b0] ^ $this->ashx[$a0];
		$a2 = $this->qt0[$n][$a1];
		$b2 = $this->qt1[$n][$b1];
		$a3 = $a2 ^ $b2;
		$b3 = $this->ror4[$b2] ^ $this->ashx[$a2];
		$a4 = $this->qt2[$n][$a3];
		$b4 = $this->qt3[$n][$b3];
		return (($b4 << 4) | $a4);
	}

	private function g0_fun($x) 
	{
		//($this->m_tab[0][$this->sb[0][$this->extract_byte($x,0)]] ^ $this->m_tab[1][$this->sb[1][$this->extract_byte($x,1)]] ^ $this->m_tab[2][$this->sb[2][$this->extract_byte($x,2)]] ^ $this->m_tab[3][$sb[3][$this->extract_byte($x,3)]]);
		//return $this->mk_tab[0 + 4*$this->extract_byte($x,0)] ^ $this->mk_tab[1 + 4*$this->extract_byte($x,1)] ^ $this->mk_tab[2 + 4*$this->extract_byte($x,2)] ^ $this->mk_tab[3 + 4*$this->extract_byte($x,3)] 
		return $this->h_fun($instance, $x, $instance->s_key);
	}

	private function g1_fun($x)
	{
		//($this->m_tab[0][$this->sb[0][$this->extract_byte($x,3)]] ^ $this->m_tab[1][$this->sb[1][$this->extract_byte($x,0)]]^ $this->m_tab[2][$this->sb[2][$this->extract_byte($x,1)]] ^ $this->m_tab[3][$sb[3][$this->extract_byte($x,2)]]);
		//return mk_tab[0 + 4*extract_byte($x,3)] ^ mk_tab[1 + 4*extract_byte($x,0)] ^ mk_tab[2 + 4*extract_byte($x,1)] ^ mk_tab[3 + 4*extract_byte($x,2)] 
		return $this->h_fun($instance, $this->rotl($x,8), $instance->s_key);
	}

	private function gen_qtab()
	{
		for($i = 0; $i < 256; ++$i)
		{
			$this->q_tab[0][$i] = $this->qp(0, $i);
			$this->q_tab[1][$i] = $this->qp(1, $i);
		}
	}

	private function gen_mtab()
	{
		$f01; $f5b; $fef;
		for($i = 0; $i < 256; ++$i)
		{
			$f01 = $this->q_tab[1][$i];
			$f5b = $this->ffm_5b($f01);
			$fef = $this->ffm_ef($f01);
			$this->m_tab[0][$i] = $f01 + ($f5b << 8) + ($fef << 16) + ($fef << 24);
			$this->m_tab[2][$i] = $f5b + ($fef << 8) + ($f01 << 16) + ($fef << 24);

			$f01 = $this->q_tab[0][$i];
			$f5b = $this->ffm_5b($f01);
			$fef = $this->ffm_ef($f01);
			$this->m_tab[1][$i] = $fef + ($fef << 8) + ($f5b << 16) + ($f01 << 24);
			$this->m_tab[3][$i] = $f5b + ($f01 << 8) + ($fef << 16) + ($f5b << 24);
		}
	}

	private function h_fun(&$instance, &$x, &$key)
	{
		$b0 = $this->extract_byte($x, 0);
		$b1 = $this->extract_byte($x, 1);
		$b2 = $this->extract_byte($x, 2);
		$b3 = $this->extract_byte($x, 3);

		switch($instance)
		{
			case 4:
				$b0 = $this->q_tab[1][$b0] ^ $this->extract_byte($key[3],0);
				$b1 = $this->q_tab[0][$b1] ^ $this->extract_byte($key[3],1);
				$b2 = $this->q_tab[0][$b2] ^ $this->extract_byte($key[3],2);
				$b3 = $this->q_tab[1][$b3] ^ $this->extract_byte($key[3],3);
			break;

			case 3:
				$b0 = $this->q_tab[1][$b0] ^ $this->extract_byte($key[2],0);
				$b1 = $this->q_tab[1][$b1] ^ $this->extract_byte($key[2],1);
				$b2 = $this->q_tab[0][$b2] ^ $this->extract_byte($key[2],2);
				$b3 = $this->q_tab[0][$b3] ^ $this->extract_byte($key[2],3);
			break;

			case 2:
				$b0 = $this->q_tab[0][($this->q_tab[0][$b0] ^ $this->extract_byte($key[1],0))] ^ $this->extract_byte($key[0],0);
				$b1 = $this->q_tab[0][($this->q_tab[1][$b1] ^ $this->extract_byte($key[1],1))] ^ $this->extract_byte($key[0],1);
				$b2 = $this->q_tab[1][($this->q_tab[0][$b2] ^ $this->extract_byte($key[1],2))] ^ $this->extract_byte($key[0],2);
				$b3 = $this->q_tab[1][($this->q_tab[1][$b3] ^ $this->extract_byte($key[1],3))] ^ $this->extract_byte($key[0],3);
			break;
		}

		$b0 = $this->q_tab[1][$b0];
		$b1 = $this->q_tab[0][$b1];
		$b2 = $this->q_tab[1][$b2];
		$b3 = $this->q_tab[0][$b3];

		$m5b_b0 = $this->ffm_5b($b0);
		$m5b_b1 = $this->ffm_5b($b1);
		$m5b_b2 = $this->ffm_5b($b2);
		$m5b_b3 = $this->ffm_5b($b3);
		$mef_b0 = $this->ffm_ef($b0);
		$mef_b1 = $this->ffm_ef($b1);
		$mef_b2 = $this->ffm_ef($b2);
		$mef_b3 = $this->ffm_ef($b3);
		$b0 ^= $mef_b1 ^ $m5b_b2 ^ $m5b_b3;
		$b3 ^= $m5b_b0 ^ $mef_b1 ^ $mef_b2;
		$b2 ^= $mef_b0 ^ $m5b_b1 ^ $mef_b3;
		$b1 ^= $mef_b0 ^ $mef_b2 ^ $m5b_b3;

		return $b0 | ($b3 << 8) | ($b2 << 16) | ($b1 << 24);
	}

	private function gen_mk_tab(&$instance, $key)
	{
		$i;
		$by;
		$mk_tab = &$instance->mk_tab;
		switch($instance)
		{
			case 2:
			for($i=0; $i<256; ++$i)
			{
				$by = $i;
				$this->sb[0][$i] = $this->q20($by);
				$this->sb[1][$i] = $this->q21($by);
				$this->sb[2][$i] = $this->q22($by);
				$this->sb[3][$i] = $this->q23($by);
			}
			break;

			case 3:
			for($i = 0; $i<256; ++$i)
			{
				$by = $i;
				$this->sb[0][$i] = $this->q30($by);
				$this->sb[1][$i] = $this->q31($by);
				$this->sb[2][$i] = $this->q32($by);
				$this->sb[3][$i] = $this->q33($by);
			}
			break;

			case 4:
			for($i = 0; $i<256; ++$i)
			{
				$by = $i;
				$this->sb[0][$i] = $this->q40($by);
				$this->sb[1][$i] = $this->q41($by);
				$this->sb[2][$i] = $this->q42($by);
				$this->sb[3][$i] = $this->q43($by);
			}
			break;
		}
	}

	private function mds_rem($p0, $p1)
	{
		$i; $u; $t;
		for($i = 0; $i<8; ++$i)
		{
			$t = $p1 >> 24;
			$p1 = ($p1 << 8) | ($p0 >> 24);
			$p0 <<= 8;
			$u = ($t << 1);
			if($t & 0x80)
			{
				$u ^= $self::G_MOD;
			}
			$p1 ^= $t ^ ($u << 16);
			$u ^= ($t >> 1);
			if($t & 0x01)
			{
				$u ^= $self::G_MOD >> 1;
			}
			$p1 ^= ($u << 24) | ($u << 8);
		}
		return $p1;
	}

	private function i_rnd($i)
	{
		$t1 = $this->g1_fun($blk[1]);
		$t0 = $this->g0_fun($blk[0]);
		$blk[2] = $this->rotl($blk[2], 1) ^ ($t0 + $t1 + $l_key[4 * ($i) + 10]);
		$blk[3] = $this->rotr($blk[3] ^ ($t0 + 2 * $t1 + $l_key[4 * ($i) + 11]), 1);

		$t1 = $this->g1_fun($blk[3]);
		$t0 = $this->g0_fun($blk[2]);
		$blk[0] = $this->rotl($blk[0], 1) ^ ($t0 + $t1 + $l_key[4 * ($i) +  8]);
		$blk[1] = $this->rotr($blk[1] ^ ($t0 + 2 * $t1 + $l_key[4 * ($i) +  9]), 1);
	}

	private function f_rnd($i)
	{
		$t1 = $this->g1_fun($blk[1]);
		$t0 = $this->g0_fun($blk[0]);
		$blk[2] = $this->rotr($blk[2] ^ ($t0 + $t1 + $l_key[4 * ($i) + 8]), 1);
		$blk[3] = $this->rotl($blk[3], 1) ^ ($t0 + 2 * $t1 + $l_key[4 * ($i) + 9]);

		$t1 = $this->g1_fun($blk[3]);
		$t0 = $this->g0_fun($blk[2]);
		$blk[0] = $this->rotr($blk[0] ^ ($t0 + $t1 + $l_key[4 * ($i) + 10]), 1);
		$blk[1] = $this->rotl($blk[1], 1) ^ ($t0 + 2 * $t1 + $l_key[4 * ($i) + 11]);
	}

	public function twoset_key(&$instance, &$in_key, &$key_len)
	{
		$i; $a; $b;
		$me_key = [];
		$mo_key = [];
		$l_key = &$instance->l_key;
		$s_key = &$instance->s_key;

		//#ifdef Q_TABLES
		if(!$qt_gen)
		{
			$this->gen_qtab();
			$qt_gen = 1;
		}
		//#endif

		//#ifdef M_TABLE
		if(!$mt_gen)
		{
			$this->gen_mtab();
			$mt_gen = 1;
		}
		//#endif

		for($i = 0; $i < $instance->k_len; ++$i)
		{
			$a = $this->SWAP_32($in_key[$i + $i]);
			$me_key[$i] = $a;
			$b = $this->SWAP_32($in_key[$i + $i + 1]);
			$mo_key[$i] = $b;
			$s_key[$instance->k_len - $i - 1] = $this->mds_rem($a, $b);
		}

		for($i=0; $i<40; $i +=2)
		{
			$a = 0x01010101 * $i;
			$b = $a + 0x01010101;

			$a = $this->h_fun($instance, $a, $me_key);
			$b = $this->rotl($this->h_fun($instance, $b, $mo_key), 8);

			$l_key[$i] = $a + $b;
			$l_key[$i + 1] = $this->rotl($a + 2 * $b, 9);
		}
		//#ifdef MK_TABLE
		$this->gen_mk_tab($instance, $s_key);
		//#endif

		return $l_key;
	}

	public function encrypt(&$instance, &$in_blk, $out_blk)
	{
		$t0; $t1;
		$blk = [];
		$l_key = &$instance->l_key;
		$mk_tab = &$instance->mk_tab;
		$blk[0] = $this->SWAP_32($in_blk[0]) ^ $l_key[0];
		$blk[1] = $this->SWAP_32($in_blk[1]) ^ $l_key[1];
		$blk[2] = $this->SWAP_32($in_blk[2]) ^ $l_key[2];
		$blk[3] = $this->SWAP_32($in_blk[3]) ^ $l_key[3];
		for($i=0;$i<=7;++$i)
		{
			$t1 = $this->g1_fun($blk[1]);
			$t0 = $this->g0_fun($blk[0]);
			$blk[2] = $this->rotr($blk[2] ^ ($t0 + $t1 + $l_key[4 * ($i) + 8]), 1);
			$blk[3] = $this->rotl($blk[3], 1) ^ ($t0 + 2 * $t1 + $l_key[4 * ($i) + 9]);

			$t1 = $this->g1_fun($blk[3]);
			$t0 = $this->g0_fun($blk[2]);
			$blk[0] = $this->rotr($blk[0] ^ ($t0 + $t1 + $l_key[4 * ($i) + 10]), 1);
			$blk[1] = $this->rotl($blk[1], 1) ^ ($t0 + 2 * $t1 + $l_key[4 * ($i) + 11]);
		}
		$out_blk[0] = $this->SWAP_32($blk[2] ^ $l_key[4]);
		$out_blk[1] = $this->SWAP_32($blk[3] ^ $l_key[5]);
		$out_blk[2] = $this->SWAP_32($blk[0] ^ $l_key[6]);
		$out_blk[3] = $this->SWAP_32($blk[1] ^ $l_key[7]);
		return $out_blk;
	}

	public function decrypt(&$instance, &$in_blk, $out_blk)
	{
		$t0; $t1;
		$blk = [];
		$l_key=&$instance->l_key;
		$mk_tab=&$instance->mk_tab;
		$blk[0] = $this->SWAP_32($in_blk[0]) ^ $l_key[4];
		$blk[1] = $this->SWAP_32($in_blk[1]) ^ $l_key[5];
		$blk[2] = $this->SWAP_32($in_blk[2]) ^ $l_key[6];
		$blk[3] = $this->SWAP_32($in_blk[3]) ^ $l_key[7];
		for($i=7;$i>=0;--$i)
		{
			$t1 = $this->g1_fun($blk[1]);
			$t0 = $this->g0_fun($blk[0]);
			$blk[2] = $this->rotl($blk[2], 1) ^ ($t0 + $t1 + $l_key[4 * ($i) + 10]);
			$blk[3] = $this->rotr($blk[3] ^ ($t0 + 2 * $t1 + $l_key[4 * ($i) + 11]), 1);

			$t1 = $this->g1_fun($blk[3]);
			$t0 = $this->g0_fun($blk[2]);
			$blk[0] = $this->rotl($blk[0], 1) ^ ($t0 + $t1 + $l_key[4 * ($i) +  8]);
			$blk[1] = $this->rotr(blk[1] ^ ($t0 + 2 * $t1 + $l_key[4 * ($i) +  9]), 1);
		}
		$out_blk[0] = $this->SWAP_32($blk[2] ^ $l_key[0]);
		$out_blk[1] = $this->SWAP_32($blk[3] ^ $l_key[1]);
		$out_blk[2] = $this->SWAP_32($blk[0] ^ $l_key[2]);
		$out_blk[3] = $this->SWAP_32($blk[1] ^ $l_key[3]);
		return $out_blk;
	}
}
?>