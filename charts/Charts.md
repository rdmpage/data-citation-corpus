# Charts

Some simple charts created from CouchDB views (replace `rows` with `values`) using [Vega-Lite](https://vega.github.io/vega-lite/).

## Top 20 repositories

![Top 20 repositories in Data Citation Corpus](https://raw.githubusercontent.com/rdmpage/data-citation-corpus/refs/heads/main/charts/repositories.png)

http://127.0.0.1:5984/data-citation-corpus-v2/_design/stats/_view/repository?reduce=true&group=true

## Top 20 publishers

![Top 20 publishers in Data Citation Corpus](https://raw.githubusercontent.com/rdmpage/data-citation-corpus/refs/heads/main/charts/publishers.png)


http://127.0.0.1:5984/data-citation-corpus-v2/_design/stats/_view/publisher?reduce=true&group=true

## GBIF

GBIF is 16th on the list of most-cited repositories.

### Top 20 publishers with data in GBIF

The top 20 publishers of articles citing data in GBIF shows Pensoft at number one. This reflects the subject matter of Pensoft journals, and Pensoft’s focus on best practices for publishing data. From GBIF’s perspective they probably want to extend their reach beyond core biodiversity journals.

![Top 20 publishers citing data from GBIF](https://raw.githubusercontent.com/rdmpage/data-citation-corpus/refs/heads/main/charts/gbif-publishers.png)

http://127.0.0.1:5984/data-citation-corpus-v2/_design/repository/_view/publisher?inclusive_end=true&start_key=%5B%22The%20Global%20Biodiversity%20Information%20Facility%22%5D&end_key=%5B%22The%20Global%20Biodiversity%20Information%20Facility%22%2C%7B%7D%5D&reduce=true&group=true


## Taylor & Francis

Taylor & Francis is the 15th most cited repository, and the vast majority of data in this repository is published by [Informa UK Ltd](https://en.wikipedia.org/wiki/Informa) which is the parent company of Taylor & Francis. 

Looking at a few of the data citations, they are all DOIs to [Figshare](https://figshare.com). Initially I thought there was some mistake, surely these DOIs should be credited to Figshare not Taylor & Francis? But it turns out that these DOIs resolve to the Taylor & Francis repository on Figshare [https://tandf.figshare.com](https://tandf.figshare.com), so this is where Taylor & Francis store supplementary data for their publications. Note that the Data Citation Corpus treats supplementary data as a “citation”, that is, a paper cites its own data.


http://127.0.0.1:5984/data-citation-corpus-v2/_design/repository/_view/publisher?inclusive_end=true&start_key=%5B%22Taylor%20%26%20Francis%22%5D&end_key=%5B%22Taylor%20%26%20Francis%22%2C%7B%7D%5D&reduce=true&group=true

## How many times are data cited?

The vast majority of data in the Data Citation Corpus is cited only once.

![Frequency of citation](https://raw.githubusercontent.com/rdmpage/data-citation-corpus/refs/heads/main/charts/cited.png)

Given that much of these “citations” may be by the publication that makes the data available, it’s not clear that the corpus is actually measuring citation (i.e., reuse of the data). Instead it may just be measuring publication. To answer this we’d need to drill down into the data more.

Note that some data items have large numbers of citations, the highest is “LY294002” with 9983 citations, with the next being “A549” with 5883 citations. [LY294002](https://en.wikipedia.org/wiki/LY294002) is a chemical compound that acts as an inhibitor, and [A549](https://en.wikipedia.org/wiki/A549_cell) is cell type. The citation corpus regards both as accession numbers for sequences. Hence it’s likely that the most cited data recoreds are not data at all, but false matches to other entities, such as chemicals and cells.




