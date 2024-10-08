# Charts

Some simple charts created from CouchDB views (replace `rows` with `values`) using [Vega-Lite](https://vega.github.io/vega-lite/).

## Top 20 repositories

http://127.0.0.1:5984/data-citation-corpus-v2/_design/stats/_view/repository?reduce=true&group=true

## Top 20 publishers

http://127.0.0.1:5984/data-citation-corpus-v2/_design/stats/_view/publisher?reduce=true&group=true

## GBIF

GBIF is 16th on the list of most-cited repositories.

### Top 20 publishers with data in GBIF

The top 20 publishers of articles citing data in GBIF shows Pensoft at number one. This reflects the subject matter of Pensoft journals, and Pensoft’s focus on best practices for publishing data. From GBIF’s perspective they probably want to extend their reach beyond core biodiversity journals.

http://127.0.0.1:5984/data-citation-corpus-v2/_design/repository/_view/publisher?inclusive_end=true&start_key=%5B%22The%20Global%20Biodiversity%20Information%20Facility%22%5D&end_key=%5B%22The%20Global%20Biodiversity%20Information%20Facility%22%2C%7B%7D%5D&reduce=true&group=true


## Taylor & Francis

Taylor & Francis is the 15th most cited repository, and the vast majority of data in this repository is published by [Informa UK Ltd](https://en.wikipedia.org/wiki/Informa) which is the parent company of Taylor & Francis. 

Looking at a few of the data citations they are all DOIs to [Figshare](https://figshare.com). Initially I thought there was some mistake, but these DOIs resolve to the Taylor & Francis repository on Figshare [https://tandf.figshare.com](https://tandf.figshare.com), so this is where Taylor & Francis store supplementary data for their publications. Note that the Data Citation Corpus treats supplementary data as a “citation”.

http://127.0.0.1:5984/data-citation-corpus-v2/_design/repository/_view/publisher?inclusive_end=true&start_key=%5B%22Taylor%20%26%20Francis%22%5D&end_key=%5B%22Taylor%20%26%20Francis%22%2C%7B%7D%5D&reduce=true&group=true