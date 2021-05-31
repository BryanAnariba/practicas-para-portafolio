import { Route, Redirect } from "react-router-dom";

import { useAuth } from '../auth/useAuth';
export const PrivateRoutes = (props) => {
    const { user } = useAuth();

    console.log(user);

    return (
        <>
        { 
            (user === null) 
            ? 
                <Redirect to="/login" />
            : 
                <Route { ...props } /> 
        }
        </>
    )
}
