# DataCite Citation Corpus

Exploring the [Data Citation Corpus](https://makedatacount.org/data-citation).

> A trusted central aggregate of all data citations to further our understanding of data usage and advance meaningful data metrics.

The data is available [by request](https://makedatacount.org/data-citation/#first-release), this repository documents my explorations of the data. 

Release announcement [DataCite launches first release of the Data Citation Corpus](https://makedatacount.org/first-release-of-the-open-global-data-citation-corpus/) doi:10.60804/r14z-mw10.

## Blog post

I wrote about the problems with the first release on iPhylo:

> Page, R. (2024). Problems with the DataCite Data Citation Corpus [https://doi.org/10.59350/t80g1-xys37](https://doi.org/10.59350/t80g1-xys37)

### Update(s)


#### Verison 1.1
There is a simplified version (v1.1) of the data available on Zenodo, see [Data Citation Corpus first release Documentation](https://makedatacount.org/data-citation-corpus-documentation/#appendix).

I’m exploring adding this to CouchDB.

> DataCite, & Make Data Count. (2024). Data Citation Corpus Data File (v1.1) [Data set]. DataCite. [10.5281/zenodo.11216814](https://doi.org/10.5281/zenodo.11216814)

#### Version 2.0

Version 2.0 was released August 23, 2024 by Datacite [doi:10.5281/zenodo.13376773](https://doi.org/10.5281/zenodo.13376773)

> Data file for the second release of the Data Citation Corpus, produced by DataCite and Make Data Count as part of an ongoing grant project funded by the Wellcome Trust. Read more about the project.

> The data file includes 5,256,114 data citation records in JSON and CSV formats. The JSON file is the version of record.

## CouchDB (version 2)


## SQL version (first release of the data)

### Data extraction

`to_sql.php` parses the JSON data and outputs SQL statements to construct a simple SQL database to explore the data. 

### Sources

The data comes from two sources, DataCite and the Chan Zuckerberg Initiative (CSI):

```
SELECT COUNT(id), sourceId FROM citation GROUP BY sourceId;
```

### Repositories 

The data repositories cited are identified by local UUIDs, so it is non-trivial to figure out which repository is which. Here is a list so far:

| Repository | id | citation count |
|--|--|--|
| GenBank | 00363b65-f3ef-4fa9-8255-23ab269f4930| 3755354 |
| PDB | 87646104-e5ef-494b-b2f3-a46c9572e003| 1729783 |
| SNP | 6087b2e9-ecbf-4898-8047-5f484f1bce2f| 890431 |
| RefSeq | 1edec4bf-cfee-4296-8893-d1b0ca528f92 | 259548 | 

### Publishers

Publishers of the journals in which citations were found are also identified using UUIDs, the top twenty of these are listed below, and names can be determined by comparing with the chart on the [corpus dashboard](http://corpus.datacite.org/dashboard).


|name | citation count | publisherId|
|--|--|--|
| | 2136164 | e566bc45-b8bc-430c-ab2c-9c224e1c6f21|
| | 1029617 | ec75ceb1-215c-4376-aa1c-4b39d15dc069|
| | 938870 | 9ead11e4-bd7d-4c91-aff0-cb962676520a|
| | 768385 | bf7ba43c-7a3e-43e3-a9c2-6ed5b6fb6303|
| | 704427 | 08d58a61-189f-4316-892b-908a1832603d|
| | 635059 | babceab8-4440-4c65-ad12-24784190dbae|
| | 315654 | 602471f4-3d02-45f7-9d59-661471761299|
| | 312135 | af7d8efb-1a44-4a02-9d5b-29ceb6878117|
| | 277952 | |
| | 276263 | 37fa820b-d158-43b4-8f67-e0c2f7364d35|
| | 199813 | 55506166-9f8d-4685-967d-c71c7af956b7|
| | 171526 | 21c1aa14-7ac4-4ccb-8fdc-8f7e3ab047a9|
| | 147908 | 2189510e-6e8f-410c-bf2a-a92319d51b0e|
| | 114627 | faca9ac2-2c88-4277-acdd-0a1177c10094|
| | 98882 | deba021e-5d63-48af-82b5-673c6507a03e|
| | 97239 | dba2ef73-893b-4c93-9123-ea3429d6c983|
| | 92100 | cfd487dd-9342-49ec-b93a-a044da079368|
| | 90016 | bd7beb5b-5e4d-4c9f-b99d-944bc8cd5bf3|
| Pensoft | 80907 | 9d72fbd4-0a14-4ee8-bac5-75ec06ababf7|
| | 80376 | c6e65534-0e8c-495f-99ed-04ee78761d3c|
| | 60503 | d2c56596-551e-4f1e-81e6-d7bafe1670f8|


#### Protein Data Bank

The [Protein Data Bank](https://www.wwpdb.org) (PDB) has 1,729,783 citations in the corpus. There are 177,220 distinct PDB identifiers cited. 

```
SELECT DISTINCT UPPER(subjId) 
FROM citation 
WHERE repositoryId = '87646104-e5ef-494b-b2f3-a46c9572e003';
```

Running these through `pub_clean.php` 31,612 (18%) do not match the PDB pattern `/^[0-9][A-Za-z0-9]{3}$/`.

I downloaded a list of all PDB identifiers from https://files.wwpdb.org/pub/pdb/holdings/current_file_holdings.json.gz, and then loaded those identifiers into the table `identifier`. 

```
SELECT COUNT(id) FROM identifier WHERE namespace='pdb'; 
```
There are 216,225 distinct PDB identifiers.

We can compare the PDB identifiers in the corpus with the actual PDB identifiers:

```
SELECT COUNT(citation.id) FROM citation 
INNER JOIN identifier 
ON UPPER(citation.subjId) = identifier.id
WHERE repositoryId = '87646104-e5ef-494b-b2f3-a46c9572e003' AND identifier.namespace = 'pdb';
```

This finds 1,233,993 PDB identifiers, which is 71% of the total in the corpus. Hence a little under a third of the PDB citations appear to be erroneous.

We can look at some mistaken identifiers:

```
SELECT citation.id, UPPER(citation.subjId), identifier.id 
FROM citation 
LEFT JOIN identifier ON UPPER(citation.subjId) = identifier.id 
WHERE repositoryId = '87646104-e5ef-494b-b2f3-a46c9572e003' 
AND citation.subjId LIKE "1%"
AND identifier.id IS NULL
LIMIT 100;
```

#### GenBank

```
select distinct subjId from citation WHERE repositoryId = '00363b65-f3ef-4fa9-8255-23ab269f4930' limit 1000;
```

Run script `genbank.php` to test for occurrence in NCBI using `esummary` query.

#### RefSeq

1edec4bf-cfee-4296-8893-d1b0ca528f92, note that 7616 citations are to Creative Commons URLs(!)

### Repository identifiers 

#### Sources

|Label | Methodology | http://identifiers.org |
|--|--|--|
|arrayexpress | https://identifiers.org/arrayexpress:dataset | Y|
|biomodels | https://identifiers.org/biomodels.db:dataset | Y|
|bioproject | https://identifiers.org/bioproject:dataset | Y|
|biosample | https://identifiers.org/biosample:dataset | Y|
|biostudies | https://identifiers.org/biostudies:dataset | Y|
|cath | https://identifiers.org/cath:dataset | Y|
|chebi | https://identifiers.org/chebi:dataset[6:] | Y|
|chembl | https://identifiers.org/chembl:dataset | Y|
|complexportal | https://identifiers.org/complexportal:dataset | Y|
|dbgap | https://identifiers.org/dbgap:dataset | Y|
|doi | https://dx.doi.org/:dataset | sometimes|
|ebisc | https://cells.ebisc.org/dataset | N|
|efo | https://identifiers.org/efo:dataset | Y|
|ega | https://identifiers.org/ega.dataset:dataset | Y|
|emdb | https://identifiers.org/emdb:dataset | Y|
|empiar | https://identifiers.org/empiar:dataset | Y|
|ensembl | https://identifiers.org/ensembl:dataset  | Y|
|gca | https://identifiers.org/insdc.gca:dataset | Y|
|gen | https://identifiers.org/ena.embl:dataset | Y|
|geo | https://identifiers.org/geo:dataset | Y|
|gisaid | http://gisaid.org/EPI/dataset | N|
|go | https://identifiers.org/go:dataset | Y|
|hgnc | https://identifiers.org/hgnc:dataset | Y|
|hipsci | http://www.hipsci.org/lines/#/lines/dataset | N|
|hpa | https://identifiers.org/hpa:dataset | Y|
|igsr | https://identifiers.org/coriell:dataset | Y|
|intact | https://identifiers.org/intact:dataset | Y|
|interpro | https://identifiers.org/interpro:dataset | Y|
|metabolights | https://identifiers.org/metabolights:dataset | Y|
|metagenomics | https://identifiers.org/mgnify.samp:dataset | Y|
|mint | https://identifiers.org/mint:dataset | Y|
|omim | https://identifiers.org/mim:dataset | Y|
|orphadata | https://identifiers.org/orphanet:dataset | Y|
|pdb | https://identifiers.org/pdb:dataset | Y|
|pfam | https://identifiers.org/pfam:dataset | Y|
|pxd | https://identifiers.org/pride:dataset | Y|
|reactome | https://identifiers.org/reactome:dataset | Y|
|refseq | https://identifiers.org/refseq:dataset | Y|
|refsnp | https://identifiers.org/dbsnp:dataset | Y|
|rfam | https://identifiers.org/rfam:dataset | Y|
|rnacentral | https://identifiers.org/rnacentral:dataset | Y|
|rrid | https://identifiers.org/rrid:dataset | Y|
|treefam | https://identifiers.org/treefam:dataset | Y|
|uniparc | https://identifiers.org/uniparc:dataset | Y|
|uniprot | https://identifiers.org/uniprot:dataset | Y|


### Gotchas

Examples of problematic identifiers.

#### Text search fails to find identifier in article, even though it is there

The citation 10.1038/s42255-020-0213-x, SAMN11157311 seemed problematic as a simple search in the online text https://www.nature.com/articles/s42255-020-0213-x found no hits. Googling `SAMN11157311` turned up the PMC version of the paper [PMC7739959](https://www.ncbi.nlm.nih.gov/pmc/articles/PMC7739959/) and `SAMN11157311` is in Table 1. This table is not displayed in the article by default, instead it’s a clickable link https://www.nature.com/articles/s42255-020-0213-x/tables/1.

#### Specimen codes become accession numbers, figure captions become PDB records

See https://doi.org/10.3897%2FBDJ.4.e8032 for 126 citations that are all incorrect.



