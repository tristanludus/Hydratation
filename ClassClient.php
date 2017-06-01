<?php

	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=facture', 'root', '');
	}
	catch (Exception $e)
	{
	        die('Erreur : ' . $e->getMessage());
	}

	$reponse = $bdd->query('SELECT NumClient, Nom, Prenom, Adresse, Ville, CodePostal, Pays FROM client');

//classe Client
class Client{

	//Données Membres
	private $_NumClient;
	private $_Nom;
	private $_Prenom;
	private $_Adresse;
	private $_CodePostal;
	private $_Ville;
	private $_Pays;

	
	private $_collectFacture=array();
	

	//Fcts Membres

	
	public function __construct($mId,$mNom,$mPrenom,$mAdr,$mCp,$mVille,$mPays){

		//echo "Contructeur <br/>";
		$this->_NumClient=$mId;
		$this->_Nom=$mNom;
		$this->_Prenom=$mPrenom;
		$this->_Adresse=$mAdr;
		$this->_CodePostal=$mCp;
		$this->_Ville=$mVille;
		$this->_Pays=$mPays;
		

	
	}

	public function __destruct(){

		

	}
	
	public function hydrate(array $donnees)
	{
		foreach ($donnees as $key => $value)
		{
			// On récupère le nom du setter correspondant à l'attribut.
			$method = 'set'.ucfirst($key);
			// Si le setter correspondant existe.
			if (method_exists($this, $method))
			{
			// On appelle le setter.
			$this->$method($value);
			}
		}
	}
	

	//Mutateurs

	public function getId(){


		return $this->_NumClient;
	}

	public function getNom(){


		return $this->_Nom;
	}

	public function getPrenom(){


		return $this->_Prenom;
	}

	public function getAdresse(){


		return $this->_Adresse;
	}

	public function getCp(){

		return $this->_CodePostal;
	}


	public function getVille(){

		return $this->_Ville;

	}

	public function getPays(){

		return $this->_Pays;

	}

	public function setIdClient($mId){

		$this->_NumClient=$mId;

	}

	public function setNom($mNom){

		$this->_Nom=$mNom;

	}

	public function setPrenom($mNom){

		$this->_Prenom=$mPrenom;

	}

	public function setAdresse($mAdresse){

		$this->_Adresse=$mAdresse;

	}

	public function setCp($mCp){

		$this->_CodePostal=$mCp;

	}

	public function setVille($mVille){

		$this->_Ville=$mVille;

	}

	public function setPays($mPays){

		$this->_Pays=$mPays;

	}

	public function affiche(){

		echo $this->_NumClient."<br/>";
		echo $this->_Nom."<br/>";
		echo $this->_Prenom."<br/>";
		echo $this->_Adresse."<br/>";
		echo $this->_CodePostal."<br/>";
		echo $this->_Ville."<br/>";
		echo $this->_Pays."<br/>";
		//echo $this->_collectFacture[$i]->affiche();"<br/>";

		// Affichage des produits dans la facture
  		foreach(self::getFactClient() as $valeur) {
 
    		echo $valeur->affiche().'<br/>';
    			
  		}

	}



	public function getFactClient(){

		return $this->_collectFacture;

	}

	public function addFature(Facture $mCollection){

		if (!in_array($mCollection,$this->_collectFacture)){
			$mCollection->setClient($this);
			$this->_collectFacture[]=$mCollection;
		}
		
			

	}


	
}

class ClientManager

{

  private $_bdd; // Instance de PDO


  public function __construct($bdd)

  {

    $this->setBdd($bdd);

  }


  public function add(Client $client)

  {

    $q = $this->_bdd->prepare('INSERT INTO client(NumClient, Nom, Prenom, Adresse, Ville, CodePostal, Pays) VALUES(:NumClient, :Nom, :Prenom, :Adresse, :Ville, :CodePostal, :Pays)');


    $q->bindValue(':NumClient', $client->NumClient(), PDO::PARAM_INT);
	
	$q->bindValue(':Nom', $client->Nom());

    $q->bindValue(':Prenom', $client->Prenom(), PDO::PARAM_INT);

    $q->bindValue(':Adresse', $client->Adresse(), PDO::PARAM_INT);

    $q->bindValue(':Ville', $client->Ville(), PDO::PARAM_INT);

    $q->bindValue(':CodePostal', $client->CodePostal(), PDO::PARAM_INT);
	
	$q->bindValue(':Pays', $client->Pays(), PDO::PARAM_INT);


    $q->execute();

  }


  public function delete(Client $client)

  {

    $this->_bdd->exec('DELETE FROM client WHERE NumClient = '.$client->NumClient());

  }


  public function get($id)

  {

    $id = (int) $id;


    $q = $this->_bdd->query('SELECT NumClient, Nom, Prenom, Adresse, Ville, CodePostal, Pays FROM client WHERE NumClient = '.$id);

    $donnees = $q->fetch(PDO::FETCH_ASSOC);


    return new Client($donnees);

  }


  public function getList()

  {

    $clients = [];


    $q = $this->_bdd->query('SELECT NumClient, Nom, Prenom, Adresse, Ville, CodePostal, Pays FROM client ORDER BY Nom');


    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))

    {

      $clients[] = new client($donnees);

    }


    return $clients;

  }


  public function update(client $client)

  {

    $q = $this->_bdd->prepare('UPDATE client SET Nom = :Nom, Prenom = :Prenom, Adresse = :Adresse, Ville = :Ville, CodePostal = :CodePostal, Pays = :Pays WHERE NumClient = :NumClient');


    $q->bindValue(':Nom', $client->Nom(), PDO::PARAM_INT);

    $q->bindValue(':Prenom', $client->Prenom(), PDO::PARAM_INT);

    $q->bindValue(':Adresse', $client->Adresse(), PDO::PARAM_INT);

    $q->bindValue(':Ville', $client->Ville(), PDO::PARAM_INT);

    $q->bindValue(':CodePostal', $client->CodePostal(), PDO::PARAM_INT);
	
	$q->bindValue(':Pays', $client->Pays(), PDO::PARAM_INT);
	
	$q->bindValue(':NumClient', $client->NumClient(), PDO::PARAM_INT);


    $q->execute();

  }


  public function setBdd(PDO $bdd)

  {

    $this->_bdd = $bdd;

  }

}

?>
<?php

$client = new Client([

  'NumClient' => 4,

  'Nom' => 'Jean',

  'Prenom' => 'Michel',

  'Adresse' => 'Grand Rue',

  'Ville' => 'Nice',
  
  'CodePostal' => 68000,
  
  'Pays' => 'France',

]);


$bdd = new PDO('mysql:host=localhost;dbname=facture', 'root', '');

$manager = new ClientManager($bdd);

    

$manager->add($client);
?>