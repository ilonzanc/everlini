import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';

function Square(props) {
  return (
    <button className="square" onClick={props.onClick}>
      {props.value}
      </button>
  );
}

class Header extends React.Component {
  render() {
    return (
      <header>
        <nav>
          <ul>
            <li><a href="/">Home</a></li>
            <li><a href="">Jouw profiel</a></li>
            <li><a href="">About</a></li>
            <li><a href="">Contact</a></li>
            <li><a href="">Inloggen</a></li>
          </ul>
        </nav>
      </header>
    )
  }
}

class Board extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      squares: Array(9).fill(null),
      xIsNext: true,
    };
  }

  handleClick(i) {
    const squares = this.state.squares.slice();
    squares[i] = this.state.xIsNext ? 'X' : 'O';
    this.setState({
        squares: squares,
        xIsNext: !this.state.xIsNext,
    });
  }

  renderSquare(i) {
    return <Square
      value={this.state.squares[i]}
      onClick={() => this.handleClick(i)}
    />;
  }

  render() {
    const status = 'Next player: ' + (this.state.xIsNext ? 'X' : 'O');

    return (
      <div>
        <div className="status">{status}</div>
        <div className="board-row">
          {this.renderSquare(0)}
          {this.renderSquare(1)}
          {this.renderSquare(2)}
        </div>
        <div className="board-row">
          {this.renderSquare(3)}
          {this.renderSquare(4)}
          {this.renderSquare(5)}
        </div>
        <div className="board-row">
          {this.renderSquare(6)}
          {this.renderSquare(7)}
          {this.renderSquare(8)}
        </div>
      </div>
    );
  }
}

class Game extends React.Component {
  render() {
    return (
      <div className="game">
        <div className="game-board">
          <Board />
        </div>
        <div className="game-info">
          <div>{/* status */}</div>
          <ol>{/* TODO */}</ol>
        </div>
      </div>
    );
  }
}

function calculateWinner(squares) {
  const lines = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6],
  ];
  for (let i = 0; i < lines.length; i++) {
    const [a, b, c] = lines[i];
    if (squares[a] && squares[a] === squares[b] && squares[a] === squares[c]) {
      return squares[a];
    }
  }
  return null;
}

 // =============================================

function FacebookLoginBtn(props) {
  return (
    <button onClick={props.onClick}>Login with Facebook</button>
  );
}

class Page extends React.Component {
  constructor(props) {
    super(props);
  }

  myFacebookLogin() {
    window.FB.login(function(){},
      {scope: 'public_profile, email' },
      //{onlogin: this.checkLoginState() },
    );
  }

  /* checkLoginState() {
    window.FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  } */

  renderFbBtn() {
    return <FacebookLoginBtn
      onClick={() => this.myFacebookLogin()}
    />;
  }

  render() {
    return (
      <div>
        <Header />
        <div className="login">
          {this.renderFbBtn()}
        </div>
      </div>
    );
  }
}

class App extends React.Component {
  render() {
    return (
      <div className="app">
        <div className="page">
          <Page />
        </div>
      </div>
    );
  }
}

  // ========================================

ReactDOM.render(
  <App />,
  document.getElementById('root')
);

window.fbAsyncInit = function() {
  window.FB.init({
    appId            : 776745922450044,
    autoLogAppEvents : true,
    xfbml            : true,
    version          : 'v2.12'
  });
};

(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "https://connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));
