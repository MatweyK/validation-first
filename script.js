'use strict';
const headings = ["Year", "Jan", "Feb", "Mar", "q1", "Apr", "May", "Jun", "Q2", "Jul", "Aug", "Sep", "Q3", "Oct", "Nov", "Dec", "Q4", "YTD"];
let tables = [
  {
    class: "table-0",
    rows: [
      {
        currentYear: "2019",
        jan: "",
        feb: "",
        mar: "",
        q1: "",
        apr: "",
        may: "",
        jun: "",
        q2: "",
        jul: "",
        aug: "",
        sep: "",
        q3: "",
        oct: "",
        nov: "",
        dec: "",
        q4: "",
        ytd: ""
      },
      
    ],
  },
  
];

const secretTemplate = document.querySelector('#secret-template');// select teamplate tag
let submitButton = document.querySelector('#submitButton')
//////////////////////////////////////////////////////////////////////////////
//ADD table btn logic start
let addNewTable = document.querySelector('#addNewTable');
addNewTable.addEventListener('click', function () {
  let rowButton = document.createElement('button');
  rowButton.className = "addRowButton";
  rowButton.textContent = "ADD ROW";
  submitButton.parentNode.insertBefore(rowButton, submitButton);
  let newTab = document.createElement('table');
  let tableRow = secretTemplate.content.cloneNode(true);

  newTab.className = "table-" + tables.length;

  let newHeadingRow = document.createElement('tr');
  newTab.appendChild(newHeadingRow);


  headings.forEach(function (element) {
    let newTabHeading = document.createElement('th');
    newTabHeading.textContent = element;
    newHeadingRow.appendChild(newTabHeading);
  });

  tableRow.querySelector('tr').className = "row-0";
  tableRow.querySelector('.currentYear').textContent = 2019;

  newTab.appendChild(tableRow);
  submitButton.parentNode.insertBefore(newTab, submitButton);

  tables.push({
    class: "table-" + tables.length,
    rows: [{
      currentYear: "2019",
      jan: "",
      feb: "",
      mar: "",
      q1: "",
      apr: "",
      may: "",
      jun: "",
      q2: "",
      jul: "",
      aug: "",
      sep: "",
      q3: "",
      oct: "",
      nov: "",
      dec: "",
      q4: "",
      ytd: ""
    }]
  });

//////////////////////////////////////
//add new row for add table

  rowButton.addEventListener('click', function (e) {
    e.preventDefault();

    let currentTableIndex = Number(e.target.nextSibling.className.substring(6));
    let currentRowIndex = tables[currentTableIndex].rows.length;
    let currentTableRows = tables[currentTableIndex].rows;
    let latestYear = Number(currentTableRows[currentTableRows.length - 1].currentYear);
    let tableNewRow = latestYear - 1;
    let tableRow = secretTemplate.content.cloneNode(true);
    tableRow.querySelector('.currentYear').textContent = tableNewRow;
    tableRow.querySelector('tr').className = 'row-' + currentRowIndex;


    newTab.insertBefore(tableRow, newTab.firstChild.nextSibling);

    currentTableRows.push({
      currentYear: tableNewRow,
      jan: "",
      feb: "",
      mar: "",
      q1: "",
      apr: "",
      may: "",
      jun: "",
      q2: "",
      jul: "",
      aug: "",
      sep: "",
      q3: "",
      oct: "",
      nov: "",
      dec: "",
      q4: "",
      ytd: ""
    });

//react on input for added table

  }, false);
  function sumQuartal(el){
    el.forEach(function(element){
      let firstMonth = Math.round(parseFloat(element.previousElementSibling.previousElementSibling.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;
        let secondMonth = Math.round(parseFloat(element.previousElementSibling.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;
        let thirdMonth = Math.round(parseFloat(element.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;

        let quartalSum = firstMonth + secondMonth + thirdMonth;
        if (quartalSum != 0) {
          element.textContent = Math.round(((quartalSum +1)/3)*100)/100;
        }
        else{
          element.textContent = 0;
        }
    }, false)
  };

 
        
     
  newTab.addEventListener('input', function (e) {
      ///@TODO adding quartalsSum
      let firstQuartals = document.querySelectorAll('.q1');
      let secondQuartals = document.querySelectorAll('.q2');
      let thirdQuartals = document.querySelectorAll('.q3');
      let fourthQuartals = document.querySelectorAll('.q4');

      sumQuartal(firstQuartals);
      sumQuartal(secondQuartals);
      sumQuartal(thirdQuartals);
      sumQuartal(fourthQuartals);

      let quartalsSum = document.querySelectorAll('.YTD');
      quartalsSum.forEach(function(element){
        let qFirst = parseFloat(element.parentNode.querySelector(':nth-child(5)').textContent);
        let qSecond = parseFloat(element.parentNode.querySelector(':nth-child(9)').textContent);
        let qThird = parseFloat(element.parentNode.querySelector(':nth-child(13)').textContent);
        let qFourth = parseFloat(element.previousElementSibling.textContent);
        let ySum = qFirst + qSecond + qThird + qFourth;
        if (ySum != 0) {
          element.textContent = Math.round(((ySum +1)/3)*100)/100;
        }
        else{
          element.textContent = 0;
        }

      }, false);
      ///////////////////////////


    let tableIndex = Number(newTab.className.substring(6));

    let currentRowIndex = e.target.parentNode.parentNode.parentNode.className.substring(4);

    let input = e.target;
    let inputClass = e.target.className;


    tables[tableIndex].rows[currentRowIndex][inputClass] = input.value;
    //
  }, false)
});


//ADD table btn logic finish
///////////////////////////////////////////////////////////////////////////////
//load data from array
document.addEventListener('DOMContentLoaded', function () {

  tables.forEach(function (element, index) {

    let rowBtn = document.createElement('button');
    rowBtn.className = "addRowButton";
    rowBtn.textContent = "ADD ROW";
    submitButton.parentNode.insertBefore(rowBtn, submitButton);

    let newTable = document.createElement('table');
    newTable.className = element.class;
    submitButton.parentNode.insertBefore(newTable, submitButton);


    let newHeadingRow = document.createElement('tr');
    newTable.appendChild(newHeadingRow);

    headings.forEach(function (element) {
      let newTableHeading = document.createElement('th');
      newTableHeading.textContent = element;
      newHeadingRow.appendChild(newTableHeading);
    })

    element.rows.forEach(function (element, index) {
      let tblRow = secretTemplate.content.cloneNode(true);
      tblRow.querySelector('tr').className = "row-" + index;
      tblRow.querySelector('.currentYear').textContent = element.currentYear;
      tblRow.querySelector('.jan').setAttribute("value", element.jan);
      tblRow.querySelector('.feb').setAttribute("value", element.feb);
      tblRow.querySelector('.mar').setAttribute("value", element.mar);
      tblRow.querySelector('.q1').textContent = element.q1;
      tblRow.querySelector('.apr').setAttribute("value", element.apr);
      tblRow.querySelector('.may').setAttribute("value", element.may);
      tblRow.querySelector('.jun').setAttribute("value", element.jun);
      tblRow.querySelector('.q2').textContent = element.q2;
      tblRow.querySelector('.jul').setAttribute("value", element.jul);
      tblRow.querySelector('.aug').setAttribute("value", element.aug);
      tblRow.querySelector('.sep').setAttribute("value", element.sep);
      tblRow.querySelector('.q3').textContent = element.q3;
      tblRow.querySelector('.oct').setAttribute("value", element.oct);
      tblRow.querySelector('.nov').setAttribute("value", element.nov);
      tblRow.querySelector('.dec').setAttribute("value", element.dec);
      tblRow.querySelector('.q4').textContent = element.q4;
      tblRow.querySelector('.YTD').textContent = element.ytd;

      newTable.insertBefore(tblRow, newTable.firstChild.nextSibling);
    })

    rowBtn.addEventListener('click', function (e) {
      e.preventDefault();
      let tblRow = secretTemplate.content.cloneNode(true);
      let currentRowsLength = element.rows.length;
      let lastYear = element.rows[currentRowsLength - 1].currentYear;
      tblRow.querySelector('tr').className = "row-" + currentRowsLength;

      tblRow.querySelector('.currentYear').textContent = lastYear - 1;
      newTable.insertBefore(tblRow, newTable.firstChild.nextSibling);

      element.rows.push({
        currentYear: lastYear - 1,
        jan: "",
        feb: "",
        mar: "",
        q1: "",
        apr: "",
        may: "",
        jun: "",
        q2: "",
        jul: "",
        aug: "",
        sep: "",
        q3: "",
        oct: "",
        nov: "",
        dec: "",
        q4: "",
        ytd: ""
      });
    }, false);

////////////////////////////////////////////////////////////////////////////////////////
  function sumQuartal(el){
    el.forEach(function(element){
      let firstMonth = Math.round(parseFloat(element.previousElementSibling.previousElementSibling.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;
        let secondMonth = Math.round(parseFloat(element.previousElementSibling.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;
        let thirdMonth = Math.round(parseFloat(element.previousElementSibling.firstChild.firstChild.value || 0)*100)/100;

        let quartalSum = firstMonth + secondMonth + thirdMonth;
        if (quartalSum != 0) {
          element.textContent = Math.round(((quartalSum +1)/3)*100)/100;;
        }
        else{
          element.textContent = 0;
        }
    }, false)
  };
//react on any change in tables inputes	that loaded
    newTable.addEventListener('input', function (e) {

      ///@TODO adding quartalsSum
      let firstQuartals = document.querySelectorAll('.q1');
      let secondQuartals = document.querySelectorAll('.q2');
      let thirdQuartals = document.querySelectorAll('.q3');
      let fourthQuartals = document.querySelectorAll('.q4');
      sumQuartal(firstQuartals);
      sumQuartal(secondQuartals);
      sumQuartal(thirdQuartals);
      sumQuartal(fourthQuartals);

      ///////////////////////////
      let quartalsSum = document.querySelectorAll('.YTD');
      quartalsSum.forEach(function(element){
        let qFirst = parseFloat(element.parentNode.querySelector(':nth-child(5)').textContent);
        let qSecond = parseFloat(element.parentNode.querySelector(':nth-child(9)').textContent);
        let qThird = parseFloat(element.parentNode.querySelector(':nth-child(13)').textContent);
        let qFourth = parseFloat(element.previousElementSibling.textContent);
        let ySum = qFirst + qSecond + qThird + qFourth;
        if (ySum != 0) {
          element.textContent = Math.round(((ySum +1)/3)*100)/100;
        }
        else{
          element.textContent = 0;
        }

      }, false);
      ///////////////////////////
      let input = e.target;
      let inputClass = e.target.className;
      let rowIndex = Number(e.target.parentNode.parentNode.parentNode.className.substring(4));
      element.rows[rowIndex][inputClass] = input.value;
    })
  })
})

//insert after pure js func
function insertAfter(elem, refElem) {
  return refElem.parentNode.insertBefore(elem, refElem.nextSibling);
}

//AJAX and quartals
submitButton.addEventListener('click', function () {
  tables.forEach(function (element, index) {
    element.rows.forEach(function (element, index){

    	let sumFirst = Math.round((parseFloat(element['jan'])|| 0)*100)/100 + Math.round((parseFloat(element['feb'])|| 0)*100)/100 + Math.round((parseFloat(element['mar'])|| 0)*100)/100;
    	if (sumFirst !=0 ) {
    		element['q1'] = Math.round(((sumFirst +1)/3)*100)/100;
    	}
    	else{
    		element['q1'] = 0;
    	}

      	let sumSecond = Math.round((parseFloat(element['apr'])|| 0)*100)/100 + Math.round((parseFloat(element['may'])|| 0)*100)/100 + Math.round((parseFloat(element['jun'])|| 0)*100)/100;
    	if (sumSecond !=0 ) {
    		element['q2'] = Math.round(((sumSecond +1)/3)*100)/100;
    	}
    	else{
    		element['q2'] = 0;
    	}

		let sumThird = Math.round((parseFloat(element['jul'])|| 0)*100)/100 + Math.round((parseFloat(element['aug'])|| 0)*100)/100 + Math.round((parseFloat(element['sep'])|| 0)*100)/100;
    	if (sumThird !=0 ) {
    		element['q3'] = Math.round(((sumThird +1)/3)*100)/100;
    	}
    	else{
    		element['q3'] = 0;
    	}       

		let sumFourth = Math.round((parseFloat(element['oct'])|| 0)*100)/100 + Math.round((parseFloat(element['nov'])|| 0)*100)/100 + Math.round((parseFloat(element['dec'])|| 0)*100)/100;
		if (sumFourth !=0 ) {
			element['q4'] = Math.round(((sumFourth +1)/3)*100)/100;
		}
		else{
			element['q4'] = 0;
		} 



        let yearSum = element['q1'] + element['q2'] + element['q3'] + element['q4'];
        if (yearSum != 0) {
        	element['ytd'] = Math.round(((yearSum +1)/4)*100)/100;
        }
        else {
        	element['ytd'] = 0;
        }
    })
  })

  // 1. Создаём новый объект XMLHttpRequest
  var xhr = new XMLHttpRequest();

  // 2. Конфигурируем его:
  xhr.open('POST', '/submit.php', true);

  // 3. Отсылаем запрос
  let sendObj = JSON.stringify(tables)
  xhr.send(sendObj);

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      alert('SUCCESS! :DDDDDDDDDD');
    }
    else if(xhr.status === 401){
      alert('Rows must have no gaps');
    }
    else if(xhr.status === 402){
      alert('The same tables values must be filled');
    }
    else {
      alert('INVALID :d');
    }
  }
  xhr.onerror = function (e) {
    console.log(e);
  }

}, false)