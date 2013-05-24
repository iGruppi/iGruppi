iGruppi
=======

Applicazione web per la gestione dei Gruppi di acquisto (Open Source software)


Introduzione
------------
Questo applicativo nasce dalle esigenze del GAS Iqbal Masih di Reggio Emilia.
I criteri fondamentali su cui si basa sono:

 - Le funzionalità di Cassa sono orientate verso il sistema del "prepagato": ogni partecipante al GAS versa una quota in anticipo e ad ogni ordine effettuato attinge da tale ammontare.
 - Ogni produttore ha un suo referente. Il referente è responsabile della gestione dei dati, dei prodotti e degli ordini (apertura/chiusura/archiviazione).
 - La figura dell'Amministratore del GAS (qui definita Fondatore) e i suoi compiti sono minimi (gestione degli utenti ed eventuali comunicazioni).


Installazione
-------------
Nella directory _documentation/database_ è presente il dump MySQL del database: igruppi_dump.sql.
Il database anche se non necessita di particolari "configurazioni". Le uniche 2 tabelle che andrebbero popolate prima di iniziare sono "categorie" e "province".

Per iniziare ad usare l'applicativo è necessario creare un dominio virtuale che punta alla directory "public".


Ringraziamenti
--------------
Grazie a mia moglie Elisa che mi ha fatto conoscere il mondo dei Gruppi di acquisto.

 
