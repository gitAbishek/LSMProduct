import { Mode } from 'react-hook-form';
import React from 'react';
import defaultTheme from '../theme/defaultTheme';

interface ThemeContextInterface {
	theme: any;
}

export const ThemeContext = React.createContext<ThemeContextInterface>({
	theme: defaultTheme,
});

interface ThemeProviderProps {
	children: React.ReactNode;
	value?: any;
}

export const ThemeProvider: React.FC<ThemeProviderProps> = ({
	children,
	value,
}) => {
	return (
		<ThemeContext.Provider value={value}>{children}</ThemeContext.Provider>
	);
};
