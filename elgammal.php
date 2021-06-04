<?php
declare(strict_types=1);

class elgammal
{
	private $base;
	private $generator;
	private $private;

	public function base()//genera base y primo
	{
		//$h=$g^$x;

	}

	public function encrypt($h, $m)//envia c1 y c2, genera "y" random
	{
		//$s= $h^$y;
		//$c1=$g^$y;
		//$c2=$m*$s;
	}

	public function decrypt($c1, $c2)//usa "x" generada y recibe c1 y c2
	{
		//$s=$c1^$x;
		//$s_inv;//$c1^($q-x);
		//$m=$c2*$s_inv;
	}
}
?>