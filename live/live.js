$(document).ready(function () {
  $.post('configuracaoTbread.php', {
    userid: sessionStorage.getItem('userId') ?? sessionStorage.getItem('userid'),
    ip: sessionStorage.getItem('ip'),
    tbreadid: sessionStorage.getItem('tbread'),
  })
    .done(function (data) {
      let retorno = JSON.parse(data)

      console.log(retorno)

      sessionStorage.setItem('clienteid', retorno.clienteid)
      sessionStorage.setItem('cliente', retorno.cliente)
      sessionStorage.setItem('nome', retorno.nome)
      sessionStorage.setItem('email', retorno.email)
      sessionStorage.setItem('cidade', retorno.cidade)
      sessionStorage.setItem('estado', retorno.estado)
      sessionStorage.setItem('subscribe_training_center', retorno.stc)
      sessionStorage.setItem('crm', retorno.crm)
      sessionStorage.setItem('ip', retorno.ip)
      sessionStorage.setItem('hash', retorno.hash ? retorno.hash : 'ND')
      sessionStorage.setItem('server', retorno.server)
      sessionStorage.setItem('activeVideo', retorno.urlVideo)
      sessionStorage.setItem('activeChat', retorno.urlChat)

      //controle de usuário ativo e inativo
      document.getElementById('enable').classList.remove('d-none')

      if (retorno.vivoplayer == 'sim' && retorno.vivo == 1) {
        document.getElementById('vivo-1').classList.remove('d-none')
      } else {
        document.getElementById('vivo-0').classList.remove('d-none')
      }

      //player
      $('#activeVideo').attr('src', retorno.urlVideo)

      //titulo evento
      $('#tituloDoChat').html(retorno.cliente)

      //chat
      $('#activeChat').attr('src', retorno.urlChat)

      //adicionando fundo na live
      $('#page-content').addClass('bg-blue')

      //time stamp para hash de login
      let timestamp = new Date().getTime()
      let hashLogin = sessionStorage.getItem('control_hash') + timestamp
      sessionStorage.setItem('hashLogin', hashLogin)

      logLogin()
    })
    .fail(function () {
      Swal.fire('Erro', 'A operação não pôde ser executada', 'error')
    })
})

//servidor
firebase.initializeApp({
  apiKey: 'AIzaSyBKuSq0NDm0n5VgTJoXfh-aUJEh2wPQMk8',
  authDomain: 'tbr-hls-player.firebaseapp.com',
  projectId: 'tbr-hls-player',
  storageBucket: 'tbr-hls-player.appspot.com',
  messagingSenderId: '942466671372',
  appId: '1:942466671372:web:3b04f63ebc7a81ed5fdf29',
})

let db = firebase.database()

let nameServer = sessionStorage.getItem('server')

db.ref('server/' + nameServer).once('value', (value) => {
  let v = value.val()
  if (v && v.state) {
    if (v.state == 'RUNNING' || v.state == 'STOPPING' || v.state == 'STARTING') {
      let iframeVideo = document.querySelector('#video-space iframe.player')
      let iframeURL = new URL(iframeVideo.src)
      let player = iframeURL.origin + iframeURL.pathname + '?'
      let params = new URLSearchParams(encodeURI(iframeURL.search))
      params.delete('startTime')
      params.delete('endTime')
      iframeVideo.src = player + decodeURIComponent(params)
    }
  }
})

window.addEventListener(
  'message',
  (msg) => {
    let data = msg.data
    if (data.type == 'action') {
      if (data.action == 'reloadpage') {
        window.location.reload()
      } else if (data.action == 'changepage') {
        window.location.href = 'https://tbread.com.br/vivo/index.php?id=' + data.data
      } else if (data.action == 'change') {
        let frame = document.querySelector('#video-space iframe.player')
        frame.src = data.data
        frame.contentDocument.location.reload(true)
      }
    }
  },
  false,
)

window.onmessage = (e) => {
  if (e.data) {
    if (e.data.command) {
      eval(e.data.command)
    }
  }
  document.querySelectorAll('iframe').forEach((iframe) => {
    iframe.contentWindow.postMessage(e.data, '*')
  })
}
// fim rotina interação player

//login
const presence = {
  pingAllowed: false,
  init: () => {
    return new Promise((resolve, reject) => {
      presence.id = localStorage.getItem('presence-id')
      if (!presence.id) {
        presence.fingerprint().then((id) => {
          presence.id = id
          localStorage.setItem('presence-id', presence.id)
          resolve()
        })
      } else {
        resolve()
      }
    })
  },
  fingerprint: () => {
    return new Promise((resolve, reject) => {
      resolve(
        Array.from(crypto.getRandomValues(new Uint8Array(16)))
          .map((b) => b.toString(16).padStart(2, '0'))
          .join(''),
      )
    })
  },
  in: (data = {}) => {
    data.id = presence.id
    if (data.room) presence.room = data.room
    presence.data('in', data)
    console.log('IN!')
  },
  out: (data = {}) => {
    data.id = presence.id
    if (presence.room) data.room = presence.room
    presence.data('out', data)
    console.log('OUT!')
  },
  ping: (data = {}) => {
    if (presence.pingAllowed) {
      data.id = presence.id
      presence.data('ping', data)
      console.log('PING!')
    }
  },
  data: (type, data) => {
    let json = btoa(JSON.stringify(data))
    var img = new Image()
    img.onload = () => {}
    img.onerror = () => {}
    let cache =
      new Date().getTime() +
      Math.random()
        .toString(36)
        .replace(/[^a-z]+/g, '')
    img.src = `https://api.tbr.com.br/presence/${type}/${json}?r=${cache}`
  },
}

let userData = {
  room: sessionStorage.getItem('clienteid'),
  client: sessionStorage.getItem('cliente'),
  name: sessionStorage.getItem('nome'),
  email: sessionStorage.getItem('email'),
  city: sessionStorage.getItem('cidade'),
  state: sessionStorage.getItem('estado'),
}

document.addEventListener('visibilitychange', () => {
  if (document.visibilityState == 'hidden') {
    presence.pingAllowed = false
    presence.out()
  } else if (document.visibilityState == 'visible') {
    presence.in(userData)
    presence.pingAllowed = true
  }
})
var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream
if (iOS) {
  window.addEventListener(
    'pagehide',
    (event) => {
      presence.pingAllowed = false
      presence.out()
    },
    false,
  )
}
try {
  presence.init().then(() => {
    presence.in(userData)
    presence.pingAllowed = true
    setInterval(() => {
      presence.ping()
    }, 180000)
  })
} catch (e) {}

function logLogin() {
  const todayLogin = new Date()
  const dthLogin = `${todayLogin.getFullYear()}/${todayLogin.getMonth() + 1}/${todayLogin.getDate()} ${todayLogin.getHours()}:${todayLogin.getMinutes()}:${todayLogin.getSeconds()}`

  const urlAPI = '//acessos.tbr.com.br/oldapi.php'

  //sessionStorage.setItem('hash', document.getElementById('hashusuario').value);

  const dataForm = {
    codigo: Number(sessionStorage.getItem('clienteid')),
    evento: sessionStorage.getItem('cliente'),
    url: document.URL,
    hash: sessionStorage.getItem('hashLogin'),
    inscrito: sessionStorage.getItem('nome'),
    email: sessionStorage.getItem('email'),
    cidade: sessionStorage.getItem('cidade'),
    estado: sessionStorage.getItem('estado'),
    ip: sessionStorage.getItem('ip'),
    login: dthLogin,
  }

  fetch(urlAPI, {
    method: 'POST',
    headers: new Headers(),
    body: JSON.stringify(dataForm),
  })
    .then((result) => result.json())
    .then((data) => {
      console.log(data)
    })
    .catch((err) => {
      //console.log( err );
    })
}

//logLogin();

const logLogout = () => {
  if (!navigator.sendBeacon) return

  const todayLogout = new Date()
  const dthLogout = `${todayLogout.getFullYear()}/${todayLogout.getMonth() + 1}/${todayLogout.getDate()} ${todayLogout.getHours()}:${todayLogout.getMinutes()}:${todayLogout.getSeconds()}`

  const urlAPI = '//acessos.tbr.com.br/oldapi.php'

  const data = {
    hash: sessionStorage.getItem('hashLogin'),
    logout: dthLogout,
  }

  //sessionStorage.clear();
  document.location.reload(true)
  navigator.sendBeacon(urlAPI, JSON.stringify(data))

  //limpar sessão php
}

window.addEventListener('beforeunload', logLogout, false)

//video-space
const chatElement = document.getElementById('chat-size')
const chatTitle = document.getElementById('chat-title')
const videoElement = document.getElementById('video-space')

function defineAltura() {
  let altura = videoElement.offsetHeight - (chatTitle.offsetHeight + 4)
  altura = altura < 400 ? 400 : altura
  //console.log(altura);
  chatElement.style.height = `${altura}px`
}

window.onresize = defineAltura
window.onload = defineAltura

function sair() {
  logLogout()
  sessionStorage.clear()
  window.location.href = '../'
}
