// ----------上のナビメニューでスクロール----------------------------
{
  const main_contents = document.getElementById('main_contents');
  const nav_items = document.getElementsByClassName('nav_item');
  const collection = Array.from(nav_items)

  const snap_point = [];
  for(let i = 0 ; i < nav_items.length ; i++ ){
    snap_point.push(i * main_contents.offsetWidth)
  }
  console.log(collection)
  for(let i=0 ; i<collection.length;i++){
    nav_items[i].addEventListener('click',function(){
      main_contents.scrollLeft=snap_point[i]
    })
  }
  main_contents.onscroll = () => {
    if(snap_point.includes(main_contents.scrollLeft)){
      const snap_point_index = snap_point.indexOf(main_contents.scrollLeft)
      const nav_items = document.getElementsByClassName('nav_item');
      const collection = Array.from(nav_items)
      collection[snap_point_index].classList.add('nav_active')
      collection.splice(snap_point_index,1)
      for(let j = 0 ; j < collection.length ; j++ ){
        collection[j].classList.remove('nav_active')
      }
    }else{
    }
  }
}
// ----------form_buttonのばつの回転----------------------------
{
  const form_button = document.getElementsByClassName('form_button')
  const form = document.getElementsByClassName('form')
  const collection = Array.from(form_button)
  for(let i = 0 ; i < form.length ; i++){
    form_button[i].addEventListener('click',function(){
      form[i].classList.toggle('form_active')
      form_button[i].classList.toggle('button_active')
    }) 
  }
}
// ----------ライトモードダークモード-----------------------------------------
{
  // const color_form =document.getElementById('setting_color')
  function set_color_mode(){
    let selected_main=document.getElementsByName('main_color')
    let selected_sub=document.getElementsByName('sub_color')
    for(let i=0;i<selected_main.length;i++){
      if(selected_main.item(i).checked){
        let set_main=selected_main.item(i).value
        localStorage.setItem('color_mode',set_main)
      }
    }
    for(let i=0;i<selected_sub.length;i++){
      if(selected_sub.item(i).checked){
        let set_sub=selected_sub.item(i).value
        localStorage.setItem('color_sub',set_sub)
      }
    }
    chenge_color_mode()
  }
  function chenge_color_mode(){
    const color_mode=localStorage.getItem('color_mode')
    const color_sub=localStorage.getItem('color_sub')
    const rootTag = document.documentElement
    switch(color_mode){
      case 'dark':
        rootTag.setAttribute('data-main','dark')
        break;
      case 'light':
        rootTag.setAttribute('data-main','light')
        break;
    }
    switch(color_sub){
      case 'blue':
        rootTag.setAttribute('data-sub','blue')
        break;
      case 'red':
        rootTag.setAttribute('data-sub','red')
        break;
      case 'green':
        rootTag.setAttribute('data-sub','green')
        break;
      case 'purple':
        rootTag.setAttribute('data-sub','purple')
        break;
    }
  }
  window.addEventListener('DOMContentLoaded', function () {
    chenge_color_mode()
  });
}
// ----------ct_resultsをリストで出す-----------------------------------------
{
  const ct_1 = document.getElementById('ct_1')
  const ct_2 = document.getElementById('ct_2')
  const rate = document.getElementById('rate')
  const ball=document.getElementsByClassName('ct_select_ball')
  
  const date = new Date();
  let flag1=true;
  let flag2=true;
  let date1 = date.getMonth() ;
  let date2 = date.getMonth() +1;

  
  function render_ct_results(date1,date2){
    let rate1 =[];
    let rate2 =[];
    let rate3 =['変化率(%)'];
    
    const ct_results_1 = ct_results.filter(function(value){
      return value.date==date1;
    })
    const ct_results_2 = ct_results.filter(function(value){
      return value.date==date2;
    })
    if(ct_results_1.length != 0){
      Object.keys(ct_results_1[0]).forEach(element=>{
        const li = document.createElement('li');
        if(element!='id' && element!='name'){
          if(element=='date'){
            li.textContent=ct_results_1[0][element]+'月';
          }else{
            li.textContent=ct_results_1[0][element]
            rate1.push(ct_results_1[0][element])
          }
          ct_1.appendChild(li);
        }
      });
      flag1=true
    }else{
      flag1=false
    }
    if(ct_results_2.length != 0){
      Object.keys(ct_results_2[0]).forEach(element=>{
        const li = document.createElement('li');
        if(element!='id' && element!='name'){
          if(element=='date'){
            li.textContent=ct_results_2[0][element]+'月';
          }else{
            li.textContent=ct_results_2[0][element];
            rate2.push(ct_results_2[0][element])
          }
          ct_2.appendChild(li);
        }
      });
      flag2=true
    }else{
      flag2=false
    }
    if(flag1&&flag2){
      for(let i=0;i<rate1.length;i++){
        if(rate1[i]!=0 && rate2[i]!=0){
          rate3.push((rate2[i]/rate1[i]*100).toPrecision(4))
        }else{
        rate3.push(null)
        }
      }
      rate3.forEach(element => {
        const li = document.createElement('li');
        li.textContent=element
        rate.appendChild(li)
      });
    }
    for(let i=0;i<ball.length;i++){
      ball[i].classList.remove('ct_selected')
    }
    ball[date1-1].classList.add('ct_selected')
    ball[date2-1].classList.add('ct_selected')


  }

  const preButton=document.getElementById('preButton')
  const nextButton=document.getElementById('nextButton')

  preButton.addEventListener('click',function(){

      date1--;
      if(date1<1){
        date1=12
      }
      date2--;
      if(date2<1){
        date2=12
      }
      while( ct_1.firstChild ){
        ct_1.removeChild( ct_1.firstChild );
      }
      while( ct_2.firstChild ){
        ct_2.removeChild( ct_2.firstChild );
      }
      while( rate.firstChild ){
        rate.removeChild( rate.firstChild );
      }
      render_ct_results(date1,date2);
  })
  nextButton.addEventListener('click',function(){

      date1++;
      if(date1>12){
        date1=1
      }
      date2++;
      if(date2>12){
        date2=1
      }
      while( ct_1.firstChild ){
        ct_1.removeChild( ct_1.firstChild );
      }
      while( ct_2.firstChild ){
        ct_2.removeChild( ct_2.firstChild );
      }
      while( rate.firstChild ){
        rate.removeChild( rate.firstChild );
      }
      render_ct_results(date1,date2);
    
  })
  render_ct_results(date1,date2);
}

