<?php
/**
 * Description of Controller_Prodotti
 *
 * @author gullo
 */
class Controller_Prodotti extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    private $_produttore;
    private $_prodotto = null;
    
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
        
        // Try to GET Produttore
        $idproduttore = $this->getParam("idproduttore");
        if(is_null($idproduttore)) {
            // Try to GET Prodotto
            $idprodotto = $this->getParam("idprodotto");
            if(!is_null($idprodotto)) {
                $prodObj = new Model_Prodotti();
                $prodotto = $prodObj->getProdottoById($idprodotto);
                if(!is_null($prodotto)) {
                    $this->_prodotto = $this->view->prodotto = $prodotto;
                    $idproduttore = $prodotto->idproduttore;
                }
            }
        }
        if(is_null($idproduttore)) {
            $this->redirect("index", "error", array('code' => 404));
        }
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        // ADD Referente object to Produttore (so I can check the ref directly into the view)
        $produttore->refObj = new Model_Produttori_Referente($produttore->iduser_ref);
        $this->_produttore = $this->view->produttore = $produttore;
        
        // check REFERENTE, controllo per i furbi (non Referenti)
        $user_ref = new Model_Produttori_Referente($produttore->iduser_ref);
        if(!$user_ref->is_Referente()) {
            $this->redirect("index", "error", array('code' => 404));
        }
        
        // Get updated if it is set
        $this->view->updated = $this->getParam("updated");        
        
    }

    function indexAction() {
        $this->forward("produttori");
    }

    
    function listAction() {
        
        // get All Prodotti by Produttore
        $objModel = new Model_Prodotti();
        $listProd = $objModel->getProdottiByIdProduttore($this->_produttore->idproduttore);
        $listProdObj = array();
        if(count($listProd) > 0)
        {
            foreach ($listProd as $value)
            {
                $listProdObj[$value->idprodotto] = new Model_Prodotti_Prodotto($value);
            }
        }
        $this->view->lpObjs = $listProdObj;        
        
        // organize by category and subCat
        $scoObj = new Model_Prodotti_SubCatOrganizer($listProd);
        $this->view->listProdotti = $scoObj->getListProductsCategorized();
        $this->view->listSubCat = $scoObj->getListCategories();
//        Zend_Debug::dump($this->view->listProdotti);die;
    }

    function editAction() {

        $idprodotto = $this->_prodotto->idprodotto;
        if(is_null($this->_prodotto)) 
        {
            $this->redirect("prodotti", "list");
        }

        $form = new Form_Prodotti();
        $form->setAction("/prodotti/edit/idprodotto/$idprodotto");
        // remove useless fields
        $form->removeField("offerta");
        $form->removeField("sconto");
        
        // set Categories
        $objCat = new Model_Categorie();
        $form->setOptions("idsubcat", $objCat->convertToSingleArray($objCat->getSubCategories($this->_userSessionVal->idgroup, $this->_prodotto->idproduttore), "idsubcat", "descrizione"));

        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {

                $this->getDB()->makeUpdate("prodotti", "idprodotto", $form->getValues());
                // REDIRECT
                $this->redirect("prodotti", "list", array("idproduttore" => $this->_prodotto->idproduttore, "updated" => true));
            }
        } else {
            $form->setValues((array)$this->_prodotto);
        }
        // Zend_Debug::dump($form); die;
        // set Form in the View
        $this->view->form = $form;
    }

    
    function addAction() {
        
        $idproduttore = $this->_produttore->idproduttore;
        
        $form = new Form_Prodotti();
        $form->setAction("/prodotti/add/idproduttore/$idproduttore");
        $form->setValue("idproduttore", $idproduttore);
        // remove useless fields
        $form->removeField("offerta");
        $form->removeField("sconto");
        $form->removeField("idprodotto");

        // set Categories
        $objCat = new Model_Categorie();
        $form->setOptions("idsubcat", $objCat->convertToSingleArray($objCat->getSubCategories($this->_userSessionVal->idgroup, $idproduttore), "idsubcat", "descrizione"));
        
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // ADD Produttore
                $this->getDB()->makeInsert("prodotti", $form->getValues());

                $this->view->added = true;
                // REDIRECT
                $this->redirect("prodotti", "list", array("idproduttore" => $fv["idproduttore"]));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }

/*
    function importlistinoAction()
    {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        $pObj = new Model_Prodotti();
        
        $idproduttore = 7;
        
        // DISATTIVO TUTTI I PRODOTTI PRESENTI IN LISTINO
        $this->getDB()->query("UPDATE prodotti SET attivo='N' WHERE idproduttore='$idproduttore'");        
        
        $row = 1;
        if (($handle = fopen("listino.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
//                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                
                // GET FIELDS from CSV
                $codice = $data[0];
                $lotto = "Lotto articolo: " . $data[1];
                $descrizione = $data[2];
                $peso = $data[3];
                $prezzo = str_replace(",", ".", $data[4]);
                $idsubcat = $data[5];
//                echo "Prezzo: $prezzo". "<br />\n";
                
                // SET MY FIELDS
                
                $sth_app = $this->getDB()->prepare("SELECT * FROM prodotti WHERE codice= :codice");
                $sth_app->execute(array('codice' => $codice));
                $prodotto = $sth_app->fetch(PDO::FETCH_OBJ);
                if( $prodotto )
                {
                    echo "TROVATO: $codice <br />";
                    echo "CSV: $descrizione - $prezzo<br />";
                    echo "DB: $prodotto->descrizione<br />";
                    
                    // UPDATE
                    $this->getDB()->query("UPDATE prodotti SET attivo='S', note='$lotto', costo='$prezzo'  WHERE codice='$codice'");
                    
                } else {
                    echo "NON TROVATO ------------------->> $codice <br />";
                    $newDesc = $descrizione . "($peso gr.)";
                    echo "$idsubcat ---> $descrizione<br />";
                    
                    // INSERT
                    $this->getDB()->query("INSERT INTO prodotti SET idproduttore='$idproduttore', idsubcat='$idsubcat', codice='$codice', descrizione='$descrizione', udm='Confezione', costo='$prezzo', note='$lotto'");
                }
                
                echo "--<br /><br />";

                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] 
                }
            }
            fclose($handle);
        }        
    }
*/        

}
?>
