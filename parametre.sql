
DROP SEQUENCE public.fournisseur_num_fourn_seq;

CREATE SEQUENCE public.fournisseur_num_fourn_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;


DROP TABLE public.fournisseur;

CREATE TABLE public.fournisseur (
	num_fourn numeric NOT NULL DEFAULT nextval('fournisseur_num_fourn_seq'::regclass),
	lib_fourn varchar NULL,
	adr_fourn varchar(128) NULL, 
	tel_fourn varchar(65) NULL,
	cel_fourn varchar(65) NULL,
	fax_fourn varchar(65) NULL,
	mail_fourn varchar(100) NULL,
	rccm_fourn varchar(75) NULL,
	cpte_contr_fourn varchar(75) NULL,
	flag_tva_fourn bool  DEFAULT true,
	flag_fourn bool  DEFAULT true,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_fournisseur PRIMARY KEY (num_fourn)
);
	
DROP SEQUENCE public.agence_seq;

CREATE SEQUENCE public.agence_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 13
	CACHE 1
	NO CYCLE;
	
DROP TABLE public.agence;

	CREATE TABLE public.agence (
	num_agce numeric NOT NULL DEFAULT nextval('agence_seq'::regclass),
	lib_agce varchar(150) NOT NULL,
	flag_agce bool DEFAULT true,
	flag_siege_agce bool DEFAULT false,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_agence PRIMARY KEY (num_agce)
);


DROP TABLE public.famille;

CREATE TABLE public.famille (
	num_fam numeric NOT NULL ,
	lib_fam varchar(175) NULL,
	flag_fam bool default true,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_famille PRIMARY KEY (num_fam)
);

DROP TABLE public.sous_famille;

CREATE TABLE public.sous_famille (
	num_sousfam numeric NOT NULL,
	num_fam numeric NOT NULL,
	lib_sousfam varchar(150) NULL,
	flag_sousfam bool DEFAULT true,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_sous_famille PRIMARY KEY (num_sousfam),
	CONSTRAINT fk_sous_famille_famille FOREIGN KEY (num_fam) REFERENCES public.famille(num_fam)
);
CREATE INDEX i_fk_sous_famille_famille ON public.sous_famille USING btree (num_fam);


-- Drop table

 DROP TABLE public.produit;

CREATE TABLE public.produit (
	num_prod numeric NOT NULL,
	num_sousfam numeric NULL,
	num_agce numeric NULL,
	lib_prod varchar(255) NOT NULL,
	prix_ht numeric NULL,
	prix_ttc numeric NULL,
	prix_achat_prod numeric NULL,
	prix_revient_prod numeric NULL,
	coef_vente_prod numeric NULL,
	flag_tva_prod int4 default 1,
	taux_tva_prod int4 NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_produit PRIMARY KEY (num_prod),
	CONSTRAINT fk_produit_sous_famille FOREIGN KEY (num_sousfam) REFERENCES public.sous_famille(num_sousfam)
);
CREATE INDEX i_fk_produit_sous_famille ON public.produit USING btree (num_sousfam);

-- Table Triggers

create trigger produit_trigg before
insert
    or
update
    on
    public.produit for each row execute function produit_fct();


CREATE OR REPLACE FUNCTION public.produit_fct()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE



a NUMERIC; b INTEGER; c INTEGER; codefa VARCHAR(15);devise INTEGER;
mt_ttc NUMERIC; mt_ht NUMERIC; mt_tva NUMERIC;tauxtva NUMERIC; codebr BIGINT; 

    BEGIN

---NE PAS OUBLIER D'ENREGISTRER LE PRMP
SELECT val_taxe INTO tauxtva FROM taxe WHERE CONST_TAXE='TVA' and flag_taxe=1;
IF (TG_OP = 'INSERT') THEN
	NEW.prix_ht := NEW.prix_ttc/(1+tauxtva/100*NEW.flag_tva_prod);   
	NEW.taux_tva_prod := tauxtva;        
END IF;

IF ((TG_OP = 'UPDATE')) THEN

	NEW.prix_ht := NEW.prix_ttc/(1+tauxtva/100*NEW.flag_tva_prod);        
    NEW.taux_tva_prod := tauxtva;
END IF ;  

    
 RETURN NEW;       
    END;
$function$
;



-- Drop table

DROP TABLE public.taxe;

CREATE TABLE public.taxe (
	code_taxe int4 NOT NULL,
	val_taxe int4 NULL,
	lib_taxe varchar(100) NULL,
	const_taxe varchar(3) NULL,
	flag_taxe bool default true,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_taxe PRIMARY KEY (code_taxe)
);


DROP TABLE public.mode_paiement;

CREATE TABLE public.mode_paiement (
	num_mpaie int4 NOT NULL, 
	lib_mpaie varchar(100) NULL,
	flag_taxe bool default true, 
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_mode_paiement PRIMARY KEY (num_mpaie)
);

DROP TABLE public.type_client;

CREATE TABLE public.type_client (
	num_typecli int4 NOT NULL,
	lib_typecli varchar(100) NULL,
	flag_typecli bool default true,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_type_client PRIMARY KEY (num_typecli)
);

CREATE SEQUENCE public.client_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;


-- Drop table

 DROP TABLE public.client;

CREATE TABLE public.client (
	num_cli numeric NOT NULL DEFAULT nextval('client_seq'::regclass),
	num_agce numeric NULL,
	num_typecli int4 NULL,
	code_cli varchar(15) NULL,
	nom_cli varchar(255) NOT NULL,
	prenom_cli varchar(255) NULL,
	local_cli varchar(255) NULL,
	adresse_cli varchar(128) NULL,
	tel_cli varchar(65) NULL,
	mail_cli varchar(100) NULL,
	cel_cli varchar(65) NULL,
	rccm_cli varchar(75) NULL,
	cpte_contr_cli varchar(75) NULL,
	adresse_geo_cli varchar(255) NULL,
	fax_cli varchar(65) NULL,
	flag_cli bool DEFAULT true,
	tva_cli int2 NULL ,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT pk_client PRIMARY KEY (num_cli),
	CONSTRAINT fk_client_type_client FOREIGN KEY (num_typecli) REFERENCES public.type_client(num_typecli),
	CONSTRAINT fk_client_agence FOREIGN KEY (num_agce) REFERENCES public.agence(num_agce)
);
