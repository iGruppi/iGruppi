<?php

class WorkerOrders {

    // elenco ordini (filtrata da idgroup)
    //  search by
    //      - data_da (Y-m-d)
    //      - data_a (Y-m-d)
    //      - stato (Pianificato, Aperto, ecc.)
    //      - idproduttore - OPTIONAL
    static function orders($request, $response, $args) {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        // get idgroup
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        $idgroup = $userSessionVal->idgroup;

        $filter = Api::payload("stato");
        if(is_null($filter))
        {
            $filter = "Aperto"; // DEFAULT value
        }

        $ordObj = new Model_Db_Ordini();
        $cObj = new Model_Db_Categorie();
        $listOrd = $ordObj->getOrdiniByIdIdgroup($idgroup);
        $ordini = [];
        if(count($listOrd) > 0) {
            foreach($listOrd AS $ordine) {
                $mooObj = new Model_Ordini_Ordine( new Model_AF_OrdineFactory() );
                $mooObj->appendDati()->initDati_ByObject($ordine);
                $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );

                // build & init Gruppi
                $mooObj->appendGruppi()->initGruppi_ByObject( $ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()) );
                $mooObj->setMyIdGroup($idgroup);

                // set Categories in Ordine object
                $categorie = $cObj->getCategoriesByIdOrdine( $mooObj->getIdOrdine() );
                $mooObj->appendCategorie()->initCategorie_ByObject($categorie);
                // add Ordine to the list checking permission and filters
                if($mooObj->canManageOrdine()) {
                    // ADD to array to view
                    if($mooObj->getStateName() == $filter) {

                        $ordini[] = $mooObj;
                    }
                }
            }
        }

        $data = [];
        if(count($ordini) > 0) {
            foreach ($ordini as $key => $ordine) {
                $data[] = [
                    'idordine' => $ordine->getIdOrdine(),
                    'descrizione' => $ordine->getDescrizione(),
                    'stato_desc' => $ordine->getStateName(),
                    'data_inizio' => $ordine->getDataInizio(),
                    'data_fine'   => $ordine->getDataFine()
                ];
            }
        }

        Api::result("OK", ["data" => $data]);
    }

    // get ordine by idordine
    // testata ordine + riepilogo prodotti ordinati
    static function orderInfo($request, $response, $args) {

        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        // get idgroup
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        $idgroup = $userSessionVal->idgroup;

        $ordCalcObj = self::_loadOrderData(Api::payload("idordine"));
        if($ordCalcObj === false) {
            Api::result("KO", ["error" => "Order not found or not accessible!"]);
        }

        // START TO BUILD DATA
        $data = [
            'idordine' => $ordCalcObj->getIdOrdine(),
            'supervisore' => Api::decorateRec('users', [
                'iduser' => $ordCalcObj->getSupervisore_IdUser(),
                'name' => $ordCalcObj->getSupervisore_Name(),
                'email' => $ordCalcObj->getSupervisore_Email(),
                'tel' => $ordCalcObj->getSupervisore_Tel(),
                'idgroup' => $ordCalcObj->getSupervisore_IdGroup(),
                'group_name' => $ordCalcObj->getSupervisore_GroupName()
            ]),
            'descrizione' => $ordCalcObj->getDescrizione(),
            'data_inizio' => $ordCalcObj->getDataInizio("Y-m-d"),
            'data_fine' => $ordCalcObj->getDataFine("Y-m-d"),
        ];

        // ADD GROUPS
        if (isset($groups[$idgroup])) {
            $data["group"] = Api::decorateRec("groups", $groups[$idgroup]);
        }

        // ADD PRODUTTORI
        if ($ordCalcObj->isMultiProduttore()) {
            foreach ($ordCalcObj->getProduttoriList() AS $idproduttore => $produttore)
                $data["produttori"][] = Api::decorateRec('produttori', ['idproduttore' => $idproduttore, 'produttore' => $produttore]);
        } else {
            $produttoriList = $ordCalcObj->getProduttoriList();
            $idproduttore = key($produttoriList);
            $produttore = $produttoriList[$idproduttore];
            $data["produttori"][] = Api::decorateRec('produttori', ['idproduttore' => $idproduttore, 'produttore' => $produttore]);
        }


        // TOTALI PRODOTTI ORDINATI
        $arProdottiOrdinati = $ordCalcObj->getProdottiOrdinati();
        if (count($arProdottiOrdinati) > 0) {
            foreach ($arProdottiOrdinati AS $pObj) {
                $arProductsGrid[] = array(
                    'idprodotto' => $pObj->getIdprodotto(),
                    'idproduttore' => $pObj->getIdproduttore(),
                    'codice' => $pObj->getCodice(),
                    'descrizione' => $pObj->getDescrizioneAnagrafica(),
                    'costo_ordine' => $pObj->getCostoOrdine(),
                    'udm' => $pObj->getUdm(),
                    'qta' => $pObj->getQta(),
                    'qta_reale' => $pObj->getQtaReale(),
                    'subcat' => $pObj->getSubCategoria(),
                    'disponibile_ordine' => ($pObj->isDisponibile() ? "SI" : "NO")
                );
            }
            $data["prodotti"] = $arProductsGrid;
        } else {
            $data["prodotti"] = [];
        }

        Api::result("OK", ["data" => $data]);
    }

    /**
     *
     *
     *
     * testata ordine + dettaglio prodotti ordinati da tutti gli utenti
     */
    /**
     * GET ordine by idordine
        Param:
     *      - idordine
     *      - tipo (totali|utenti) - Default: utenti
     *      -
     *
     * @param $request
     * @param $response
     * @param $args
     */
    static function orderDelivery($request, $response, $args) {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        // get idgroup
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        $idgroup = $userSessionVal->idgroup;

        $ordCalcObj = self::_loadOrderData(Api::payload("idordine"));
        if($ordCalcObj === false) {
            Api::result("KO", ["error" => "Order not found or not accessible!"]);
        }

        // START TO BUILD DATA
        $data = [
            'idordine' => $ordCalcObj->getIdOrdine(),
            'supervisore' => Api::decorateRec('users', [
                'iduser' => $ordCalcObj->getSupervisore_IdUser(),
                'name' => $ordCalcObj->getSupervisore_Name(),
                'email' => $ordCalcObj->getSupervisore_Email(),
                'tel' => $ordCalcObj->getSupervisore_Tel(),
                'idgroup' => $ordCalcObj->getSupervisore_IdGroup(),
                'group_name' => $ordCalcObj->getSupervisore_GroupName()
            ]),
            'descrizione' => $ordCalcObj->getDescrizione(),
            'data_inizio' => $ordCalcObj->getDataInizio("Y-m-d"),
            'data_fine' => $ordCalcObj->getDataFine("Y-m-d"),
        ];

        // ADD GROUPS
        if (isset($groups[$idgroup])) {
            $data["group"] = Api::decorateRec("groups", $groups[$idgroup]);
        }

        // ADD PRODUTTORI
        if ($ordCalcObj->isMultiProduttore()) {
            foreach ($ordCalcObj->getProduttoriList() AS $idproduttore => $produttore)
                $data["produttori"][] = Api::decorateRec('produttori', ['idproduttore' => $idproduttore, 'produttore' => $produttore]);
        } else {
            $produttoriList = $ordCalcObj->getProduttoriList();
            $idproduttore = key($produttoriList);
            $produttore = $produttoriList[$idproduttore];
            $data["produttori"][] = Api::decorateRec('produttori', ['idproduttore' => $idproduttore, 'produttore' => $produttore]);
        }


        // DETTAGLIO PRODOTTI ORDINATI
        if ($ordCalcObj->getProdottiUtenti() > 0) {
            foreach ($ordCalcObj->getProdottiUtenti() AS $iduser => $dataUser) {

                $data_destinatari['utente'] = Api::decorateRec('users', $dataUser["user"]);

                foreach ($dataUser["prodotti"] AS $idprodotto => $pObj) {
                    $data_destinatari['prodotti'][] = [
                        'prodotto' => [
                            'idprodotto'    => $idprodotto,
                            'descrizione'   => $pObj->getDescrizioneAnagrafica()
                        ],
                        'qta'       => $pObj->getQta_ByIduser($dataUser["user"]->iduser),
                        'qta_reale' => $pObj->getQtaReale_ByIduser($dataUser["user"]->iduser)
                    ];
                }

                $data["destinatari"][] = $data_destinatari;
            }
        }

        Api::result("OK", ["data" => $data]);
    }


    private static function _loadOrderData($idordine)
    {

        // get idgroup
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        $idgroup = $userSessionVal->idgroup;

        $ordObj = new Model_Db_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine, $idgroup);
        if(!is_null($ordine)) {
            // build Ordine
            $mooObj = new Model_Ordini_Ordine(new Model_AF_UserOrdineFactory());
            // build & init DATI Ordine
            $mooObj->appendDati()->initDati_ByObject($ordine);
            // build & init STATE Ordine
            $mooObj->appendStates(Model_Ordini_State_OrderFactory::getOrder($ordine));

            // build & init Gruppi
//            Zend_Debug::dump($ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()));die;
            $mooObj->appendGruppi()->initGruppi_ByObject($ordObj->getGroupsByIdOrdine($mooObj->getIdOrdine()));
            $mooObj->setMyIdGroup($idgroup);

            // creo elenco prodotti
            $prodottiModel = new Model_Db_Prodotti();
            $listProd = $prodottiModel->getProdottiByIdOrdine($idordine);

            // build & init CATEGORIE
            $mooObj->appendCategorie()->initCategorie_ByObject($listProd);

            // build & init PRODOTTI
            $mooObj->appendProdotti()->initProdotti_ByObject($listProd);

            // init Model DB Ordini
            $ordObj = new Model_Db_Ordini();

            // GET elenco GRUPPI che hanno ordinato
            $groups = $ordObj->getGroupsWithAlmostOneProductOrderedByIdOrdine($mooObj->getIdOrdine());

            // add CALCOLI DECORATOR
            $ordCalcObj = new Model_Ordini_CalcoliDecorator($mooObj);
            $ordCalcObj->setIdgroup($idgroup);

            // SET PRODOTTI ORDINATI in DECORATOR
            $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($mooObj->getIdOrdine(), $idgroup);
            $ordCalcObj->setProdottiOrdinati($listProdOrdered);

            // Zend_Debug::dump($groups);die;

            return $ordCalcObj;
        }

        return false;

    }

}