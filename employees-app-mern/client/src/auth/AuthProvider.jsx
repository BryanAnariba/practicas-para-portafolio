import { createContext, useState } from 'react';

export const AuthContext = createContext();

export const AuthProvider = ({children}) => {
    const [user, setUser] = useState(null);
    
    const contextValue = {
        user
    }
    return (<AuthContext.Provider value={ contextValue } >
        { children }
    </AuthContext.Provider>);
}