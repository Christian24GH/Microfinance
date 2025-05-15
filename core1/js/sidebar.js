document.addEventListener('DOMContentLoaded', function(){
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const toggler = document.getElementById('sidebartoggler');

    let sidebarW = "20rem";
    function saveState(value){
        sessionStorage.setItem('state', value);
    }

    function loadState(){
        let state = sessionStorage.getItem('state');
        return state;
    }

    if(loadState() == 'close'){
        sidebar.style.width = '0';
        main.style.marginLeft = '0';
    }else{
        sidebar.style.width = sidebarW;
        main.style.marginLeft = sidebarW;
    }

    sidebar.classList.remove('visually-hidden');
    main.classList.remove('visually-hidden');

    function toggle(){
        let state = loadState();
        console.log(state);

        if(state == 'open'){
            sidebar.style.width = '0';
            main.style.marginLeft = '0';
            saveState('close');

        }else if (state == 'close'){
            sidebar.style.width = sidebarW;
            main.style.marginLeft = sidebarW;
            saveState('open');
        }else{
            saveState('close');
        }
    }

    toggler.addEventListener('click', toggle);

});


