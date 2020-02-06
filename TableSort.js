// TableSort 10.3 vom 27. 1. 2020
// Jürgen Berkemeier
// www.j-berkemeier.de
// Das Script steht unter des Lizenz: CC BY-SA 4.0 (Namensnennung - Weitergabe unter gleichen Bedingungen 4.0 International)

(function() {
	
	"use strict";

	var JB_tableSort = function(tab,startsort) {
		
		var initTableHead = function(col) { // Kopfzeile vorbereiten
			if(tabletitel[col].className.indexOf("sortier")==-1) {
				return false;
			}
			if(tabletitel[col].className.indexOf("sortierbar-")>-1) {
				firstsort[col] = "desc";
			}
			else if(tabletitel[col].className.indexOf("sortierbar")>-1) {
				firstsort[col] = "asc";
			}
			var sortbutton = document.createElement("button");
			sortbutton.innerHTML = "<span class='visually-hidden'>" + sort_hint.asc + "</span>" + "<span class='visually-hidden'>" + sort_hint.desc + "</span>" + tabletitel[col].innerHTML;
			sortbutton.className = "sortbutton";
			sortbutton.type = "button";
			var sortsymbol = null;
			var symbolspan = sortbutton.querySelectorAll("span");
			if(symbolspan && symbolspan.length) {
				for(var i=0;i<symbolspan.length;i++) {
					if(!symbolspan[i].hasChildNodes()) { 
						sortsymbol = symbolspan[i];
						break;
					}
				}
			}
			if(!sortsymbol) {
				sortsymbol = document.createElement("span");
				sortbutton.appendChild(sortsymbol);
			}
			sortsymbol.classList.add("sortsymbol");
			if(tabletitel[col].className.indexOf("vorsortiert-")>-1) {
				sortbutton.classList.add("sorteddesc");
				sorted = col;
			}
			else if(tabletitel[col].className.indexOf("vorsortiert")>-1) {
				sortbutton.classList.add("sortedasc");
				sorted = col;
			}
			else {
				sortbutton.classList.add("unsorted");
			}
			if(tabletitel[col].className.indexOf("sortiere-")>-1) {
				startsort_d = col;
			}
			else if(tabletitel[col].className.indexOf("sortiere")>-1) {
				startsort_u = col;
			}
			sortbutton.addEventListener("click",function() { tsort(col); },false);
			tabletitel[col].innerHTML = "<span class='visually-hidden'>" + tabletitel[col].innerHTML + "</span>";
			tabletitel[col].appendChild(sortbutton);
			tabletitel[col].abbr = "";
			sortsymbols[col] = sortsymbol;
			sortbuttons[col] = sortbutton;
			return true;
		} // initTableHead

		// Bereich prüfen
		var between = function(num,min,max) {	
			return ( num >= min && num <= max );
		} // between
			
		// Datum trimmen
		var trmdat = function(dmy) { 
			if(dmy[0]<10) dmy[0] = "0" + dmy[0];
			if(dmy[1]<10) dmy[1] = "0" + dmy[1];
			if(dmy[2]<10) dmy[2] = "200" + dmy[2];
			else if(dmy[2]<30) dmy[2] = "20" + dmy[2];
			else if(dmy[2]<99) dmy[2] = "19" + dmy[2];
			else if(dmy[2]>9999) dmy[2] = "9999";
			return dmy;
		} // trmdat
		
		var getData = function (ele, col) {
			var dmy,val,tval,dp;
			
			// Tabellenfelder auslesen
			if (ele.getAttribute("data-sort_key")) 
				val = ele.getAttribute("data-sort_key");
			else if (ele.getAttribute("sort_key")) 
				val = ele.getAttribute("sort_key");
			else 
				val = ele.textContent;
			// val = ele.textContent.trim().replace(/\s+/g," ")

			// Zahl
			if(sorttype[col] == "n"){
				// auf Datum/Zeit prüfen
				if(!val.search(/^\s*\d+\s*\.\s*\d+\s*\.\s*\d+\s+\d+:\d\d\:\d\d\s*$/)) {  // dd. mm. yyyy hh:mm:ss
					dp = val.search(":");
					dmy = val.substring(0,dp-2).split(".");
					dmy[3] = val.substring(dp-2,dp);
					dmy[4] = val.substring(dp+1,dp+3);
					dmy[5] = val.substring(dp+4,dp+6);
					for(var i=0;i<6;i++) dmy[i] = parseInt(dmy[i],10);
					dmy = trmdat(dmy);
					for(var i=3;i<6;i++) if(dmy[i]<10) dmy[i] = "0" + dmy[i];
					if(debugmodus) console.log(val+": dd. mm. yyyy hh:mm:ss");
					if(between(dmy[0],1,31) && between(dmy[1],1,12)) 
						return (""+dmy[2]+dmy[1]+dmy[0]+"."+dmy[3]+dmy[4]+dmy[5]).replace(/ /g,"");
				}
				if(!val.search(/^\s*\d+\s*\.\s*\d+\s*\.\s*\d+\s+\d+:\d\d\s*$/)) { // dd. mm. yyyy hh:mm
					dp = val.search(":");
					dmy = val.substring(0,dp-2).split(".");
					dmy[3] = val.substring(dp-2,dp);
					dmy[4] = val.substring(dp+1,dp+3);
					for(var i=0;i<5;i++) dmy[i] = parseInt(dmy[i],10);
					dmy = trmdat(dmy);
					for(var i=3;i<5;i++) if(dmy[i]<10) dmy[i] = "0"+dmy[i];
					if(debugmodus) console.log(val+": dd. mm. yyyy hh:mm");
					if(between(dmy[0],1,31) && between(dmy[1],1,12)) 
						return (""+dmy[2]+dmy[1]+dmy[0]+"."+dmy[3]+dmy[4]).replace(/ /g,"");
				}
				if(!val.search(/^\s*\d+\s*\.\s*\d+\s*\.\s*\d+/)) { // dd. mm. yyyy
					dmy = val.split(".");
					for(var i=0;i<3;i++) dmy[i] = parseInt(dmy[i],10);
					dmy = trmdat(dmy);
					if(debugmodus) console.log(val+": dd. mm. yyyy")
					if(between(dmy[0],1,31) && between(dmy[1],1,12)) 
						return (""+dmy[2]+dmy[1]+dmy[0]).replace(/ /g,"");
				}
				if(!val.search(/^\s*\d+:\d\d\:\d\d\s*$/)) { // hh:mm:ss
					dmy = val.split(":");
					for(var i=0;i<3;i++) dmy[i] = parseInt(dmy[i],10);
					for(var i=0;i<3;i++) if(dmy[i]<10) dmy[i] = "0"+dmy[i];
					if(debugmodus) console.log(val+": hh:mm:ss");
					return ("."+dmy[0]+dmy[1]+dmy[2]).replace(/ /g,"");
				}
				if(!val.search(/^\s*\d+:\d\d\s*$/)) { // hh:mm
					dmy = val.split(":");
					for(var i=0;i<2;i++) dmy[i] = parseInt(dmy[i],10);
					for(var i=0;i<2;i++) if(dmy[i]<10) dmy[i] = "0"+dmy[i];
					if(debugmodus) console.log(val+": hh:mm");
					return ("."+dmy[0]+dmy[1]).replace(/ /g,"");
				}

				// Tausendertrenner entfernen, und , durch . ersetzen ...
				tval = val.replace(/\s|&nbsp;|&#160;|\u00A0|&#8239;|\u202f|&thinsp;|&#8201;|\u2009/g,"").replace(",", ".");
				
				// ... und auf Zahl prüfen
				if (!isNaN(tval) && tval.search(/[0-9]/) != -1) return tval;    

				// Einheiten etc. entfernen und dann auf Zahl prüfen
				tval = val.replace(",", ".");
				tval = parseFloat(tval);
				if (!isNaN(tval)) return tval;    
			}
			
			// String
			sorttype[col] = "s"; 
			return val;
		} // getData		

		var vglFkt_s = function(a,b) {
			var ret = a[sorted].localeCompare(b[sorted],doclang);
			if(!ret && sorted != minsort) {
				if(sorttype[minsort] == "s") ret = a[minsort].localeCompare(b[minsort],doclang);
				else                         ret = a[minsort] - b[minsort];
			}
			return ret;
		} // vglFkt_s

		var vglFkt_n = function(a,b) {
			var ret = a[sorted] - b[sorted];
			if(!ret && sorted != minsort) {
				if(sorttype[minsort] == "s") ret = a[minsort].localeCompare(b[minsort],doclang);
				else                         ret = a[minsort] - b[minsort];
			}
			return ret;
		} // vglFkt_n

		// Der Sortierer
		var tsort = this.tsort = function(col) { 
			if(typeof(JB_presort)=="function") JB_presort(tab,tbdy,tr,nrows,ncols,col);
			
			if(debugmodus) console.log(tab,col,sorttype[col]);

			if(col == sorted) { // Tabelle ist schon nach dieser Spalte sortiert, also nur Reihenfolge umdrehen
				arr.reverse();
				sortbuttons[col].classList.toggle("sortedasc"); 
				sortbuttons[col].classList.toggle("sorteddesc"); 
				tabletitel[col].abbr = (tabletitel[col].abbr==sort_info.asc)?sort_info.desc:sort_info.asc;
			}
			else {              // Sortieren 
				if(sorted>-1) {
					sortbuttons[sorted].classList.remove("sortedasc");
					sortbuttons[sorted].classList.remove("sorteddesc");
					sortbuttons[sorted].classList.add("unsorted");
					tabletitel[sorted].abbr = "";
				}
				sorted = col;
				sortbuttons[col].classList.remove("unsorted");
				if(sorttype[col] == "n") arr.sort(vglFkt_n);
				else                     arr.sort(vglFkt_s);
				if(firstsort[col] == "desc") {
					arr.reverse();
					sortbuttons[col].classList.add("sorteddesc");
					tabletitel[col].abbr = sort_info.desc;
				}
				else {
					sortbuttons[col].classList.add("sortedasc");
					tabletitel[col].abbr = sort_info.asc;
				}
			}
			
			// Sortierte Daten zurückschreiben
			for(var r=0;r<nrows;r++) tbdy.appendChild(arr[r][ncols]); 

			// Aktuelle sortierung speichern
			if(savesort) {  
				var store = { sorted: sorted, desc: sortsymbols[sorted].className.indexOf("sorteddesc")>-1};
				localStorage.setItem(tab.id,JSON.stringify(store));
			}

			// Callbackfunktion aufrufen
			if(typeof(JB_aftersort)=="function") JB_aftersort(tab,tbdy,tr,nrows,ncols,col);
		} // tsort
		
		// Tabelle zum Sortieren vorbereiten
		
		// Callbackfunktion aufrufen
		if(typeof(JB_presortinit)=="function") JB_presortinit(tab,-1,-1,-1,-1,-1);

		// Dokumentensprache ermitteln
		var doclang = document.documentElement.lang || "de"; 
		
		// Debugmodus?
		var debugmodus = (location.search.toLowerCase().search("debugmodus")!=-1);

		// Tabellenelemente ermitteln
		var thead = tab.tHead;
		if(thead) {
			var tr_in_thead = thead.querySelectorAll("tr.sortierbar");
			if(!tr_in_thead.length) tr_in_thead = thead.rows;
		}
		if(tr_in_thead) var tabletitel = tr_in_thead[0].cells;   
		if( !(tabletitel && tabletitel.length > 0) ) { console.error("Tabelle hat keinen Kopf (thead) und/oder keine Kopfzellen."); return null; }
		var tbdy = tab.tBodies;
		if( !(tbdy) ) { console.error("Tabelle hat keinen tbody."); return null; }
		tbdy = tbdy[0];
		var tr = tbdy.rows;
		if( !(tr && tr.length > 0) ) { console.error("Tabelle hat keine Zeilen im tbody."); return null; }
		var nrows = tr.length;
		var ncols = tabletitel.length;

		// Einige Variablen
		var arr = [];
		var sorted = -1;
		var sortsymbols = [];
		var sortbuttons = [];
		var sorttype = [];
		var firstsort = [];
		var startsort_u = -1,startsort_d = -1;
		var savesort = tab.className.indexOf("savesort")>-1 && tab.id && tab.id.length>0 && localStorage && location.protocol != "file:";
		var minsort = -1;

		// Hinweistexte
		var sort_info, sort_hint;
		if(doclang == "de") {
			sort_info = {
				asc: "Tabelle ist aufsteigend nach dieser Spalte sortiert",
				desc: "Tabelle ist absteigend nach dieser Spalte sortiert",
			};
			sort_hint = {
				asc: "Sortiere aufsteigend nach ",
				desc: "Sortiere absteigend nach ",
			}
		}
		else {
			sort_info = {
				asc: "Table is sorted by this column in ascending order",
				desc: "Table is sorted by this column in descending order",
			};
			sort_hint = {
				asc: 'Sort ascending by ',
				desc: 'Sort descending by ',
			}
		}

		// Stylesheets für Button im TH
		if(!document.getElementById("JB_stylesheet_tableSort")) {
			var sortbuttonStyle = document.createElement('style'); 
			sortbuttonStyle.id = "JB_stylesheet_tableSort";
			var stylestring = '.sortbutton { width:100%; height:100%; border: none; background-color: transparent; font: inherit; color: inherit; text-align: inherit; padding: 0; cursor: pointer; } ';		
			stylestring += 'table.sortierbar thead th span.visually-hidden, table[sortable] thead th span.visually-hidden { position: absolute !important; clip: rect(1px, 1px, 1px, 1px) !important; padding: 0 !important; border: 0 !important; height: 1px !important; width: 1px !important; overflow: hidden !important; white-space: nowrap !important; } ';
			stylestring += '.sortsymbol::after { display: inline-block; letter-spacing: -.2em; margin-left:.1em; width: 1.8em; } ';
			stylestring += '.sortbutton.sortedasc .sortsymbol::after { content: "▲▽" } ';
			stylestring += '.sortbutton.sorteddesc .sortsymbol::after { content: "△▼" } ';
			stylestring += '.sortbutton.unsorted .sortsymbol::after { content: "△▽" } '	;
			stylestring += '.sortbutton.sortedasc > span.visually-hidden:nth-of-type(1) { display: none } ' ;
			stylestring += '.sortbutton.sorteddesc > span.visually-hidden:nth-of-type(2) { display: none } ' ;
			stylestring += '.sortbutton.unsorted > span.visually-hidden:nth-of-type(2) { display: none } ' ;
			stylestring += 'table.sortierbar caption span{ font-weight: normal; font-size: .8em; } ';
			sortbuttonStyle.innerText = stylestring;
			document.head.appendChild(sortbuttonStyle);
		}

		// Prüfen, ob kein tr im thead eine entsprechnde Klasse hat
		var sortflag = false;
		for(var c=0;c<tabletitel.length;c++) sortflag |= tabletitel[c].className.indexOf("sortier")>-1;
		if(!sortflag)	for(var c=0;c<tabletitel.length;c++) tabletitel[c].classList.add("sortierbar");
		
		// Kopfzeile vorbereiten
		for(var c=tabletitel.length-1;c>=0;c--) if(initTableHead(c)) minsort = c;
		
		// Array mit Info, wie Spalte zu sortieren ist, vorbelegen
		for(var c=0;c<ncols;c++) sorttype[c] = "n";
		
		// Tabelleninhalt in ein Array kopieren
		for(var r=0;r<nrows;r++) {
			arr[r] = [];
			for(var c=0;c<ncols;c++) {
				var cc = getData(tr[r].cells[c],c);
				arr[r][c] = cc ;
				if(debugmodus) tr[r].cells[c].innerHTML += "<br>"+cc+"<br>"+sorttype[c];
			}
			arr[r][ncols] = tr[r];
		}
		if(debugmodus) {
			for(var c=0;c<ncols;c++) tabletitel[c].innerHTML += "<br>" + sorttype[c];
		}
		
		// Tabellenfelder, die als String sortiert werden sollen, in Strings konvertieren
		for(var c=0;c<ncols;c++) {
			if(sorttype[c] == "s") {
				for(var r=0;r<nrows;r++) {
					arr[r][c] = String(arr[r][c]);
				}
			}
		}
		
		// Tabelle die Klasse "is_sortable" geben
		tab.classList.add("is_sortable");

		// An caption Hinweis anhängen
		var caption = tab.caption;
		if(caption) caption.innerHTML += doclang=="de"?
			"<br><span>Ein Klick auf die Spaltenüberschrift sortiert die Tabelle.</span>":
			"<br><span>A click on the column header sorts the table.</span>";
			
		// Bei Bedarf sortieren
		if(startsort && typeof(startsort.sorted)!="undefined" && typeof(startsort.desc)!="undefined") {
			if(startsort.desc) { startsort_d = startsort.sorted; startsort_u = -1; }
			else               { startsort_u = startsort.sorted; startsort_d = -1; }
		}
		if(startsort_u >= 0 && startsort_u < ncols) tsort(startsort_u); 
		if(startsort_d >= 0 && startsort_d < ncols) { tsort(startsort_d); tsort(startsort_d); }
		
		// Callbackfunktion aufrufen
		if(typeof(JB_aftersortinit)=="function") JB_aftersortinit(tab,tbdy,tr,nrows,ncols,-1);
	
	} // tableSort

	// Alle Tabellen suchen, die sortiert werden sollen, und den Tabellensortierer starten, wenn gewünscht, alte Sortierung wiederherstellen.
	if(window.addEventListener) window.addEventListener("DOMContentLoaded",function() { 
		var sort_Table = document.querySelectorAll("table.sortierbar, table[sortable]");
		for(var i=0,store;i<sort_Table.length;i++) {
			store = null;
			if(location.protocol != "file:" && localStorage && sort_Table[i].className && sort_Table[i].id && sort_Table[i].className.indexOf("savesort")>-1 && sort_Table[i].id.length) {
				store = localStorage.getItem(sort_Table[i].id);
				if(store) {
					store = JSON.parse(store);
				}
			}
			new JB_tableSort(sort_Table[i],store);
		}
	},false); // initTableSort

})();  