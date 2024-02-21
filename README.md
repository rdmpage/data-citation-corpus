# DataCite Citation Corpus

Exploring the [Data Citation Corpus](https://makedatacount.org/data-citation).

> A trusted central aggregate of all data citations to further our understanding of data usage and advance meaningful data metrics.

The data is available [by request](https://makedatacount.org/data-citation/#first-release), this repository documents my explorations of the data. 



## Data extraction

`to_sql.php` parses the JSON data and outputs SQL statements so we can construct a simple SQL database to explore the data. 

### Sources

The data comes from two sources, DataCite and the Chan Zuckerberg Initiative (CSI):

```
SELECT COUNT(id), sourceId FROM citation GROUP BY sourceId;
```

### Repositories 

The data repositories that are cited are identified by local UUIDs, so it is non-trivial to figure out which repository is which. Here is a list so far:

| Repository | id | citation count |
|--|--|--|
| GenBank | 00363b65-f3ef-4fa9-8255-23ab269f4930| 3755354 |
| PDB | 87646104-e5ef-494b-b2f3-a46c9572e003| 1729783 |
| SNP | 6087b2e9-ecbf-4898-8047-5f484f1bce2f| 890431 |

### Publishers

Publishers of the journals in which citations were found are also identified using UUIDs, the top twenty of these are listed below, and names can be determined by comparing to the chart on the [corpus dashboard](http://corpus.datacite.org/dashboard).


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


## Protein Data Bank

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
ON citation.subjId = identifier.id
WHERE repositoryId = '87646104-e5ef-494b-b2f3-a46c9572e003' AND identifier.namespace = 'pdb';
```

This finds 842,451 PDB identifiers, which is 49% of the total in the corpus. Hence just over half the putative PDB citations are erroneous.




