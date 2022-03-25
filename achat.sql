-- Drop table

-- DROP TABLE public.commandefour;

CREATE TABLE public.commandefour (
	num_comf numeric NOT NULL,
	num_agce numeric NOT NULL,
	num_fourn numeric NOT NULL,
	date_cre_comf timestamp NULL DEFAULT now(),
	date_val_comf timestamp NULL,
	flag_tva_comf int4 NULL DEFAULT 1,
	mont_comf numeric NULL DEFAULT 0,
	flag_comf int4 NULL DEFAULT 0,
	solde_comf int4 NULL DEFAULT 0,
	annule_comf int4 NULL DEFAULT 0,
	num_agt numeric NULL,
	prix_ht_comf numeric NULL DEFAULT 0,
	prix_tva_comf numeric NULL DEFAULT 0,
	num_pdel int8 NULL,
	prix_ttc_comf numeric NULL DEFAULT 0,
	maj int2 NULL DEFAULT 0,
	flag_arsi_comf int2 NULL DEFAULT 0,
	flag_comf_br int2 NULL DEFAULT 0,
	comment_com text NULL,
	code_pd numeric NULL,
	cout_act numeric NULL DEFAULT 0,
	mont_comf_dev numeric NULL DEFAULT 0,
	CONSTRAINT pk_commandefour PRIMARY KEY (num_comf),
	CONSTRAINT fk_commandefour_agence FOREIGN KEY (num_agce) REFERENCES public.agence(num_agce),
	CONSTRAINT fk_commandefour_fournisseur FOREIGN KEY (num_fourn) REFERENCES public.fournisseur(num_fourn)
);
CREATE INDEX i_fk_commandefour_agence ON public.commandefour USING btree (num_agce);
CREATE INDEX i_fk_commandefour_fournisseur ON public.commandefour USING btree (num_fourn);

-- Table Triggers

create trigger commmandefour_trigg before
insert
    or
update
    on
    public.commandefour for each row execute function commmandefour_fct();
