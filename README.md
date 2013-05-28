iGruppi
=======

Applicazione web per la gestione dei Gruppi di acquisto (Open Source software)
Website: http://igruppi.com


Introduzione
------------
Questo applicativo nasce dalle esigenze del GAS Iqbal Masih di Reggio Emilia.
I criteri fondamentali su cui si basa sono:

 - Le funzionalità di Cassa sono orientate al sistema del "prepagato": ogni partecipante al GAS versa una quota in anticipo. Le spese per ogni ordine effettuato vengono poi scalate di volta in volta dal saldo di tale quota.
 - Ogni produttore ha un suo referente. Il referente è responsabile della gestione dei dati, dei prodotti e degli ordini (apertura/chiusura/archiviazione).
 - La figura dell'Amministratore del GAS (qui definita Fondatore) e i suoi compiti sono minimi (gestione degli utenti ed eventuali comunicazioni).


Installazione
-------------
Nella directory _documentation/database_ è presente il dump MySQL del database: *igruppi_dump.sql*.
Le uniche 2 tabelle del database che andrebbero popolate sono _categorie_ (categorie prodotti) e _province_.
Per iniziare ad usare l'applicativo è necessario creare un dominio virtuale che punta alla directory *public*.


Ringraziamenti
--------------
Ringrazio mia moglie Elisa per avermi fatto conoscere il mondo dei Gruppi di acquisto.

 
