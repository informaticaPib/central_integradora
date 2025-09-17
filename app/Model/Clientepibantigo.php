<?php
	class Clientepibantigo extends AppModel
	{
		public $useTable = 'membro';
		public $useDbConfig = 'sistemapibvelho';
		
		public function totalComVinculo(){
			$x = $this->find("count", array(
				"fields" => "Clientepibantigo.id",
				"conditions" => "Clientepibantigo.discipulador_ci != 0 AND Clientepibantigo.liberado_entrevista = 0 AND Clientepibantigo.agendado = 0 AND (Clientepibantigo.idStatus = 29 OR Clientepibantigo.idStatus = 27 OR Clientepibantigo.idStatus = 26)"
			));
			
			return $x;
		}
		public function totalComVinculoStandBy(){
			$x = $this->find("count", array(
				"fields" => "Clientepibantigo.id",
				"conditions" => "Clientepibantigo.discipulador_ci != 0 AND Clientepibantigo.liberado_entrevista = 0 AND Clientepibantigo.agendado = 0 AND Clientepibantigo.idStatus = 30"
			));
			
			return $x;
		}
		
		public function totalSemVinculo(){
			$x = $this->find("count", array(
				"fields" => "Clientepibantigo.id",
				"conditions" => "Clientepibantigo.discipulador_ci = 0 AND Clientepibantigo.liberado_entrevista = 0 AND Clientepibantigo.agendado = 0 AND (Clientepibantigo.idStatus = 29 OR Clientepibantigo.idStatus = 27 OR Clientepibantigo.idStatus = 26)"
			));
			
			return $x;
		}
	}