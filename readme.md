# PC Configurator

## Descrizione pagine

- **add_to_cart.php**: questa pagina, valida che l'utente sia loggato, in caso lo sia prende da sessione l'id dell'utente e l'id della configurazione, copia tutti i componenti della configurazione e li salva nel carrello dell'utente. In caso l'utente non sia loggato, viene reindirizzato alla pagina di login.
  add_to_cart non è una pagina html ma una pagina da chiamare a livello api con fetch.
- **buy.php**: questa pagina è di esempio per quando l'utente vuole comprare quello che c'è nel carrello, in questo caso viene semplicemente svuotato il carrello e inserito un ordine nella tabella ordini.
  anche questa pagina non ha html ma va chiamata con fetch.
- **cart.php**: questa pagina mostra il carrello dell'utente con tutti i componenti che ha aggiunto, viene mostrato nome, prezzo (con eventuali sconti) ed immagine del componente, inoltre viene mostrato il prezzo totale del carrello.
  ogni componente può essere rimosso dal carrello tramite il pulsante "rimuovi dal carrello".
- **catalogo.php**: la pagina catalogo mostra tutte le categorie di prodotti disponibili, se nell'url si specifica un parametro "cat" con l'id della categoria allora verranno mostrati tutti i prodotti di tale categoria, altrimenti verranno mostrate tutte le categorie.
- **config.php**: config.php è il file che contiene tutte le informazioni per la connessione al database, viene incluso in tutte le pagine php.
- **configuratore.php**: configuratore crea un form in base alle categorie disponibili nel database, ogni categoria ha un select con tutti i componenti di quella categoria, quando si crea una configurazione il backend crea un id univoco che identifica una configurazione e viene salvata nel database, salvare questa informazione permette di:
  - condividere la configurazione con altre persone tramite link catalogo.php?conf=<confID>
  - fare login in caso l'utente non sia loggato, se ritorna sulla pagina di configurazione la configurazione non viene persa
  - salvare la configurazione nel carrello
- **home_page.php**: questa pagina è la home page del sito, richiede al database le news e le offerte che l'admin vuole mostrare e le mostra in due sezioni separate.
- **login.php**: prende per form email e password dell'utente e valida che siano corrette, in caso lo siano viene salvato l'id dell'utente sulla sessione e viene reindirizzato alla home page, altrimenti viene mostrato un messaggio di errore.
- **logout.php**: svuota la sessione e reindirizza alla home page in automatico dopo 5 secondi.
- **navbar.php**: questa pagina contiene la navbar del sito, viene inclusa in tutte le pagine, mostra la sezione di login in caso l'utente non sia loggato altrimenti mostra la sezione utente.
- **pagina_di_presentazione.php**: questa pagina mostra una breve presentazione dell'azienda.
- **register.php**: questa pagina permette all'utente di registrarsi, richiede un username, email e password (con richiesta di verifica password), se l'utente inserisce dati validi viene creato un nuovo utente nel database e viene automaticamente loggato, altrimenti viene mostrato un messaggio di errore.
- **scheda_prodotto.php**: questa pagina mostra le informazioni di un prodotto, viene mostrato il nome, la descrizione, il prezzo (con eventuali sconti), l'immagine e le informazioni definite sul database.
- **session.php**: session.php contiene tutte le funzioni per gestire la sessione, viene incluso in tutte le pagine.
- **update_config.php**: questa pagina aggiorna la configurazione sul database (viene chiamata ogni volta che l'utente cambia select in configuratore.php).
  prende l'id della configurazione della sessione e l'id del componente da aggiungere per GET, controlla che l'id di configurazione e l'id del componente siano validi, se lo sono allora prende la categoria del componente e controlla se nella configurazione c'è già un altro componente della stessa categoria, se c'è allora il vecchio componente della stessa categoria viene sovrascritto del nuovo altrimenti viene semplicemente aggiunti un nuovo record.
- **user.php**: questa pagina mostra le informazioni dell'utente, viene mostrato l'username, l'email e la data di registrazione, il carrello corrente e la cronologia degli ordini.
  in fondo alla pagina c'è il bottone per fare logout.

## Descrizione database

- **categories**: contiene tutte le categorie dei prodotti, ha un immagine di defaul da mostrare in catalogo.php così da rendere la pagina più bella da vedere senza leggere il testo, poi un isConfigurationRequired che indica se la configurazione deve contenere un elemento di questa categoria per essere una configurazione completa, poi il nome della configurazione.
- **brands**: brands ha tutti i brand dei prodotti, ha un nome ed un immagine, inoltre ogni brand ha categoryID che indica a quale categoria appartiene. (ad esempio amd può appartenere sia ai processori che alle schede video).
- **components**: contiene tutti i componenti, ogni componente ha un brand e una categoria (brandID e categoryID), un nome e una descrizione, un prezzo e una percentuale di sconto da applicare (0 by default), un booleano che indica se il componente è disponibile o meno, un url di review (ad esempio se il componente è stato trattato su un sito come tom's hardware) e un url con l'immagine del componente (se l'immagine è caricata sul backend basta mettere immagini/nomeimmagine).
- **componentsInfo**: questa tabella raggruppa tutte le info che si vogliono mostrare all'utente, ad esempio una cpu ha un numero di core, numero di thread e velocità mentre una ram ha dimensione e velocità, al posti di avere tutto in una tabella statica questo approccio ci permette di avere un numero dinamico di informazioni per componente.
- **users**: la tabella users contiene tutti gli utenti, la password è in hash md5 (quindi lunghezza statica di 32), poi il parametri isAdmin indica se l'utente ha i permessi di loggare sulla pagina per gli admin.
- **cartContents**: cart contents ha tutti i componenti che si trovano in un carrello di un utente, contiene l'id dell'utente e l'id del componente, ogni utente ha un solo carrello ma può avere più componenti (inoltre la quantità di ogni componente, di default 1).
- **configs**: la tabella configs ha tutte le configurazione, ogni configurazione ha un uuid per rendere più facile la condivisione ma più difficile la scoperta di configurazioni altrui usando semplimente un numero più piccolo (ad esempio se la tua configurazione è la n 4 allora vuol dice che ci sono le configurazioni 1 2 3 che possono essere scoperte, mentre con uuid non è così semplice).
- **configContents**: config contents è la relazione tra la configurazione e i componenti, contiene l'id della configurazione e l'id del componente, ogni configurazione può avere più componenti e ogni componente può essere in più configurazioni.
- **orders**: orders contiene tutti gli ordini di ogni utente, quando l'utente decide di ordinare quello che ha nel carrello viene creato un nuovo record in ordini, tutti i componenti vengono copiati in ordersComponents ed hanno come id l'id dell'ordina appena creato, inoltre lo stato dell'ordine viene messo in pending. (quando si preme il bottone paga sul frontend allora l'ordine viene messo in stato pagato), gli stati di un ordine sono:
  - **pending**: l'ordine è stato creato ma non ancora pagato
  - **paid**: l'ordine è stato pagato, deve essere processato
  - **processing**: l'ordine è in fase di processamento
  - **failed**: l'ordine è fallito, può essere fallito per diversi motivi, ad esempio se un componente non è più disponibile, in questo caso l'admin dovrebbe mandare una mail all'utente per avvisarlo.
- **ordersContents**: orders contents ha tutti i componenti conentuti in un ordine
- **offers**: questa tabella viene usata nella homepage per mostrare eventuali offerte che l'admin vuole mostrare all'utente, l'offerta ha un titolo per l'homepage e l'id del componente.
- **news**: news, come offers, contiene le news da mostrare nella homepage, contiene un titolo, descrizione, immagine e un link alla news completa.
