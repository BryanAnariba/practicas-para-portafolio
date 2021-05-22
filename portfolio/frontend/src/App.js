import './index.css';
import './animate.min.css';

import { Header } from './components/header/Header';
import { Home } from './components/home/Home';
import { About } from './components/about/About';
import { Service  } from './components/service/Service';
import { Skills  } from './components/skills/Skills';

function App() {
  return (
    <>
      <Header/>
      <Home />
      <About />
      <Service />
      <Skills />
    </>
  );
}

export default App;
