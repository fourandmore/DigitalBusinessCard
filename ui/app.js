const API='/digital-business-card/admin/cards';
const LOGIN='/digital-business-card/admin/login';
const LOGOUT='/digital-business-card/admin/logout';
let cards=[];
let token='';
const ids=['id','slug','firstName','lastName','displayName','position','company','phone','email','website','street','postalCode','city','country','logoUrl','brandsJson','imprintUrl','privacyUrl','footerClaim','active'];
const $=id=>document.getElementById(id);
const authHeaders=json=>{const h={};if(token)h['X-DBC-Admin-Token']=token;if(json)h['Content-Type']='application/json';return h};
function note(t=''){$('notice').textContent=t}
function data(){const o={};ids.forEach(k=>o[k]=$(k).value);return o}
function fill(c={}){ids.forEach(k=>{if($(k))$(k).value=c[k]??(k==='active'?'1':'')});renderList(c.id)}
function renderList(active){$('list').innerHTML='';cards.forEach(c=>{const b=document.createElement('button');b.textContent=(c.displayName||`${c.firstName||''} ${c.lastName||''}`.trim()||c.slug)+`  /${c.slug}`;if(c.id==active)b.classList.add('active');b.onclick=()=>fill(c);$('list').appendChild(b)})}
async function authenticate(){const key=prompt('Verwaltungsschlüssel für DigitalBusinessCard:')||'';if(!key)throw new Error('Kein Verwaltungsschlüssel eingegeben.');const r=await fetch(LOGIN,{method:'POST',headers:{'X-DBC-Admin-Key':key,'Content-Type':'application/json'},credentials:'same-origin',body:'{}'});const j=await r.json();if(!r.ok)throw new Error(j.error||'Anmeldung fehlgeschlagen.');token=j.token||'';if(!token)throw new Error('Anmeldung fehlgeschlagen.')}
async function load(){try{if(!token)await authenticate();const r=await fetch(API,{headers:authHeaders(false),credentials:'same-origin'});const j=await r.json();if(!r.ok)throw new Error(j.error||'API-Fehler');cards=j;renderList();if(cards[0])fill(cards[0])}catch(e){note(e.message||'Die Backend-API konnte nicht geladen werden.')}}
$('newBtn').onclick=()=>fill({active:1,company:'Four & more GmbH',country:'Germany',footerClaim:'QUALITÄT FÜR GENERATIONEN',brandsJson:'[]'});
$('form').onsubmit=async e=>{e.preventDefault();note('');let d=data(),id=parseInt(d.id||0);delete d.id;try{JSON.parse(d.brandsJson||'[]')}catch(x){note('Marken-JSON ist ungültig.');return}const r=await fetch(id?`${API}/${id}`:API,{method:id?'PUT':'POST',headers:authHeaders(true),credentials:'same-origin',body:JSON.stringify(d)});const j=await r.json();if(!r.ok){note(j.error||'Speichern fehlgeschlagen.');return}await load();fill(j);note('Gespeichert.')};
$('deleteBtn').onclick=async()=>{const id=parseInt($('id').value||0);if(!id||!confirm('Karte wirklich löschen?'))return;const r=await fetch(`${API}/${id}`,{method:'DELETE',headers:authHeaders(false),credentials:'same-origin'});if(!r.ok){note('Löschen fehlgeschlagen.');return}await load()};
$('openBtn').onclick=()=>{const s=$('slug').value.trim();if(s)window.open(`/visitenkarte/${encodeURIComponent(s)}`,'_blank')};
window.addEventListener('beforeunload',()=>{if(token)fetch(LOGOUT,{method:'POST',headers:authHeaders(false),credentials:'same-origin',keepalive:true})});
load();
