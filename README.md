iGruppi
=======

Applicazione web per la gestione dei Gruppi di acquisto (Free software)

Website: http://igruppi.com


Introduzione
------------
Questo applicativo nasce dalle esigenze del GAS Iqbal Masih di Reggio Emilia.
I criteri fondamentali su cui si basa sono:

 - Le funzionalità di Cassa sono orientate al sistema del "prepagato": ogni partecipante al GAS versa una quota in anticipo. Le spese per ogni ordine effettuato vengono poi scalate di volta in volta dal saldo di tale quota.
 - Ogni produttore ha un unico Gestore (a livello di sistema) e uno o più Referenti (per ogni GAS). Leggi qui per [maggiori dettagli sui Ruoli](https://github.com/iGruppi/iGruppi/wiki/Ruoli) presenti in iGruppi.
 - La figura dell'Amministratore del GAS (qui definita Fondatore) e i suoi compiti sono minimi (gestione degli utenti ed eventuali comunicazioni).


Installazione ambiente di sviluppo
-------------
Per ottenere rapidamente l'ambiente di lavoro potete creare una macchina virtuale già predisposta e realizzata ad hoc per iGruppi. Seguire la procedura descritta in [iGruppi/Vagrant-Dev-Env](https://github.com/iGruppi/Vagrant-Dev-Env).

Installazione ambiente in produzione
-------------
Nella directory _documentation/database_ è presente il dump MySQL del database: *igruppi2_create-db.sql*.
Le uniche tabelle del database che andrebbero popolate sono _categorie_ (categorie prodotti), _groups_ (inserendo il primo gruppo), _users_ (inserendo il primo utente del suddetto gruppo) e _users_group_ (creando la relazione tra i precedenti 2).
Per iniziare ad usare l'applicativo è necessario creare un dominio virtuale che punta alla directory *public*.


Ringraziamenti
--------------
Ringrazio mia moglie Elisa per avermi fatto conoscere il mondo dei Gruppi di acquisto.

 
