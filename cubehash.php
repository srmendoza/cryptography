<?php
declare(strict_types=1);

class cubehash
{
	/*
	private $ROUNDS=16;

			private:

			$m_buffer_pos;
			$m_buffer[32];

		public:

		public $HashSize;
		public $BlockSize;

			public function CubeHash($a_hashSize)
			{
				$BlockSize = 32;
				$HashSize = $a_hashSize;
				$this->Initialize();
			}
		*/

	private function TransformBlock()
	{
		$y=[];

		for($r = 0; $r < $this->ROUNDS; ++$r) 
		{
			for($i=0; $i<16; $i++) 
			{
				$this->m_buffer[$i + 16] += $this->m_buffer[$i];
			}
			for($i=0; $i<16; $i++) 
			{
				$y[$i ^ 8] = $this->m_buffer[$i];
			}
			for($i=0; $i<16; $i++) 
			{
				$this->m_buffer[$i] = $y[$i] << 7 | $y[$i] >> 25;
			}
			for($i=0; $i<16; $i++) 
			{
				$this->m_buffer[$i] ^= $this->m_buffer[$i + 16];
			}
			for($i=0; $i<16; $i++) 
			{
				$y[$i ^ 2] = $this->m_buffer[$i + 16];
			}
			for($i=0; $i<16; $i++) 
			{
				$this->m_buffer[$i + 16] = $y[$i];
			}
			for($i=0; $i<16; $i++) 
			{
				$this->m_buffer[$i + 16] += $this->m_buffer[$i];
			}
			for($i=0; $i<16; $i++) 
			{
				$y[$i ^ 4] = $this->m_buffer[$i];
			}
			for($i=0; $i<16; $i++) 
			{
				$this->m_buffer[$i] = $y[$i] << 11 | $y[$i] >> 21;
			}
			for($i=0; $i<16; $i++) 
			{
				$this->m_buffer[$i] ^= $this->m_buffer[$i + 16];
			}
			for($i=0; $i<16; $i++) 
			{
				$y[$i ^ 1] = $this->m_buffer[$i + 16];
			}
			for($i=0; $i<16; $i++) 
			{
				$this->m_buffer[$i + 16] = $y[$i];
			}
		}
	}

	public function Initialize()
	{
		for($i=0; $i<32; $i++)
		{
			$this->m_buffer[$i] = 0;
		}

		$this->m_buffer[0] = $this->HashSize;
		$this->m_buffer[1] = $this->BlockSize;
		$this->m_buffer[2] = $this->ROUNDS;

		for($i = 0; $i<10; $i++)
		{
			$this->TransformBlock();
		}

		$this->m_buffer_pos = 0;
	}

	public function TransformBytes(&$a_data, $a_length)
	{
		while($a_length >= 1) 
		{
			$u = &$a_data;
			$u <<= 8 * (($this->m_buffer_pos / 8) % 4);
			$this->m_buffer[$this->m_buffer_pos / 32] ^= $u;
			$a_data += 1;
			$a_length -= 1;
			$this->m_buffer_pos += 8;
			if($this->m_buffer_pos === 8 * $this->BlockSize)
			{
				$this->TransformBlock();
				$this->m_buffer_pos = 0;
			}
		}
	}

	private function TransformFinal()
	{
		$this->Finish();
		$result = $this->GetResult();
		$this->Initialize();
		return $result;
	}

	public function Finish()
	{
		$u = (128 >> ($this->m_buffer_pos % 8));
		$u <<= 8 * (($this->m_buffer_pos / 8) % 4);
		$this->m_buffer[$this->m_buffer_pos / 32] ^= $u;
		$this->TransformBlock();
		$this->m_buffer[31] ^= 1;
		for($i=0; $i<10; $i++) 
		{
			$this->TransformBlock();
		}
	}

	private function GetResult()
	{
		$result = [];

		for($i=0; $i<$this->HashSize; $i++) 
		{
			$result[$i] = $this->m_buffer[$i / 4] >> (8 * ($i % 4));
		}

		return $result;
	}

	private function ComputeBytes(&$a_data, $a_length)
	{
		$this->Initialize();
		$this->TransformBytes($a_data, $a_length);
		return $this->TransformFinal();
	}
}
?>