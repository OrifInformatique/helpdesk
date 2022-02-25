function initMigrationView(){
    document.querySelectorAll('#login-bar .col-sm-12.col-md-3.text-right').forEach((loginBarLink)=>{loginBarLink.remove()});
    if (countSelectedElement()>=2){
        displayMultiSelector();
    }
    if (window.location.href.includes('?')){
    window.history.replaceState(null,null,window.location.href.split('?')[0]);
    }

    document.querySelectorAll('.migrationTable:not(.migrationHistoryTable) tbody tr').forEach((tableMigrationRow)=>{
        tableMigrationRow.addEventListener('click',(event)=>{
            if (event.target.tagName!=='INPUT')
                tableMigrationRow.querySelector('input').checked=!tableMigrationRow.querySelector('input').checked;
        })
    });
    document.querySelector('.migrationTable:not(.migrationHistoryTable)').addEventListener('click',(event)=>{

        if (countSelectedElement()>=2){
            displayMultiSelector();
        }
        else{
            hideMultiSelector();
        }
    });
}
function moveSelector(direction){
    if (direction==='r') {
        document.querySelector('.migrationViewHeaderSelector').style.left = '50%';
        document.querySelector('.migrationHistoryTable').style.display='table'
        document.querySelector('.migrationTable').style.display='none'
        document.cookie = "selected=history";

    }

    else{
        document.querySelector('.migrationViewHeaderSelector').style.left = '10.1%';
        document.querySelector('.migrationTable').style.display='table'
        document.querySelector('.migrationHistoryTable').style.display='none'
        document.cookie = `selected=migration;`;

    }
}
function displayMultiSelector(){
    document.querySelectorAll('.multipleControlMigration').forEach((element)=>{
        element.style.display='flex';
    });

}
function hideMultiSelector(){
    document.querySelectorAll('.multipleControlMigration').forEach((element)=>{
        element.style.display='none';
    });
}
function countSelectedElement(){
    let count=0;
    document.querySelectorAll('.migrationTable:not(.migrationHistoryTable) tbody tr td input').forEach((checkbox)=>{
        if (checkbox.checked){
            count++;
        }
    });
    document.querySelectorAll('.migrationSelectedElement').forEach((element)=>{
        element.innerText=count;
    })
    return count;
}
function displaySpinner(){
    let spinner=document.createElement('div');
    spinner.id='spinnerContainer';
    let background=document.createElement('div');
    background.className='background';
    for (let i=0;i<3;i++){
        let element=document.createElement('div');
        element.className='item';
        background.appendChild(element);
    }
    spinner.appendChild(background);
    document.body.appendChild(spinner);
}
function removeSpinner(){
    document.getElementById('spinnerContainer')!==null?document.getElementById('spinnerContainer').remove():null;
}
async function migrateMultipleFile(){
    displaySpinner();

    const migrationLinks=[];
    document.querySelectorAll('.migrationTable input[type=checkbox]').forEach((checkbox)=>{
        if (checkbox.checked===true){
            const checkLine=(checkbox.closest('tr'));
            const migrateLink=checkLine.querySelector('a[href*="migrate/"]');
            const tdCreDate=checkLine.querySelector('td:nth-of-type(4)');
            migrationLinks.push({creationDate:new Date(tdCreDate.innerText).getTime(),link:migrateLink.getAttribute('href')});

        }
    })
    migrationLinks.sort((element,nextElement)=>{
        if (element.creationDate>nextElement.creationDate){
            return 1;
        }
        else if(element.creationDate<nextElement.creationDate){
            return -1
        }
    });
    for (let i=0;i<migrationLinks.length;i++){
        const migration=migrationLinks[i];
        const response=await fetch(migration.link);
        await response.text();

        if (response.url.includes('?error')){
            window.location.href=response.url;
            return;
        }
    }
    return window.location.reload(true);


}
async function removeMultipleFile(){
    displaySpinner();
    const removemigrationLinks=[];
    document.querySelectorAll('.migrationTable input[type=checkbox]').forEach((checkbox)=>{
        if (checkbox.checked===true){
            const checkLine=(checkbox.closest('tr'));
            const removemigrateLink=checkLine.querySelector('a[href*="remove/"]');
            const tdCreDate=checkLine.querySelector('td:nth-of-type(4)');
            removemigrationLinks.push({creationDate:new Date(tdCreDate.innerText).getTime(),link:removemigrateLink.getAttribute('href')+'/2'});

        }
    })
    removemigrationLinks.sort((element,nextElement)=>{
        if (element.creationDate>nextElement.creationDate){
            return -1;
        }
        else if(element.creationDate<nextElement.creationDate){
            return 1
        }
    });
    for (let i=0;i<removemigrationLinks.length;i++){
        const migration=removemigrationLinks[i];
        const response=await fetch(migration.link);
        await response.text();

        if (response.url.includes('?error')){
            return window.location.href=response.url;
        }
    }
    return window.location.refresh(true);
}