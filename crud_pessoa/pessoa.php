<?php

class Pessoa {
	
	private $pdo;
	//CONEXÃO COM O BANCO DE DADOS
	public function __construct($dbname, $host, $user, $senha)
	{
		try {
		$this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
		}
		catch(PDOException $e){
			echo "Erro com banco de dados: ".$e->getMessage();
			exit();
		}
		catch(Exception $e){
			echo "Erro genérico: ".$e->getMessage();
			exit();
		}
	}
	//FUNÇÃO PARA BUSCAR OS DADOS E COLOCAR NO BANCO DIREITO DA TELA
	public function buscarDados()
	{
		$res = array();
		$cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
		
		$res = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	//FUNÇÃO DE CADASTRAR PESSOAS NO BANCO DE DADOS
	public function cadastrarPessoas($nome,$telefone,$email){
		//ANTES DE CADASTRAR VAMOS VERIFICAR SE JÁ POSSUÍ O EMAIL
		$cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
		$cmd->bindValue(":e",$email);
		$cmd->execute();
		if($cmd->rowCount() > 0){//se o emial já existe no banco
			return false;
		} else{ //o email não foi encontrado no banco
			$cmd = $this->pdo->prepare("INSERT INTO pessoa (nome,telefone,email) VALUES (:n,:t,:e)");
			$cmd->bindValue(":n",$nome);
			$cmd->bindValue(":t",$telefone);
			$cmd->bindValue(":e",$email);
			$cmd->execute();
			return true;
		}
	}
	
	//FUNÇÃO DE EXCLUIR PESSOAS NO BANCO DE DADOS
	public function excluirPessoa($id){
	$cmd = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
	$cmd->bindValue(":id",$id);
	$cmd->execute();
	}
	
	//BUSCAR DADOS DE UMA PESSOA
	public function buscarDadosPessoa($id){
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
		$cmd->bindValue(":id",$id);
		$cmd->execute();
		$res = $cmd->fetch(PDO::FETCH_ASSOC);
		return $res;
	}



	//ATUALIZAR DADOS NO BANCO DE DADOS
	public function atualizarDados($id, $nome, $telefone, $email)
	{
		$cmd= $this->pdo->prepare("UPDATE pessoa SET nome =:n, telefone = :t, email = :e WHERE id = :id");
		$cmd->bindValue(":n",$nome);
		$cmd->bindValue(":t",$telefone);
		$cmd->bindValue(":e",$email);
		$cmd->bindValue(":id",$id);
		$cmd->execute();
	}
}

?>