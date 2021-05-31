import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';

import { HomePage } from '../pages/not-found/HomePage';
import { Account } from '../components/account/Account';
import { Admin } from '../components/admin/Admin';
import { Login } from '../components/login/Login';
import { Project } from '../components/project/Project';
import { Projects } from '../components/projects/Projects';
import { Register } from '../components/register/Register';
import { NotFound } from '../pages/not-found/NotFound';

import { Layout } from '../layouts/Layout';
import { PrivateRoutes } from './PrivateRoutes';
import { PublicRoutes } from './PublicRoutes';

// MERN Stack 3 - Creando el Router - Mongo DB Express React JS Node, Luis Cabrera
export const AppRoutes = () => {
    return (
        <Router>
            <Layout>
                <Switch>
                    <PublicRoutes exact path="/" component={ HomePage }/>
                    <PublicRoutes exact path="/login" component={ Login }/>
                    <PublicRoutes exact path="/register" component={ Register }/>
                    <PrivateRoutes exact path="/account" component={ Account }/>
                    <PrivateRoutes exact path="/projects" component={ Projects }/>
                    <PrivateRoutes exact path="/project/:projectId" component={ Project }/>
                    <PrivateRoutes exact path="/admin/users" component={ Admin }/>

                    <Route path="*" component={ NotFound }/>
                </Switch>
            </Layout>
        </Router>
    )
}
