import 'bootstrap/dist/css/bootstrap.min.css';

import { AppRoutes } from './routes/AppRoutes';
import { AuthProvider } from './auth/AuthProvider';


function App() {
  return (
    <> 
      <AuthProvider>
        <AppRoutes/>
      </AuthProvider>
    </>
  );
}

export default App;
