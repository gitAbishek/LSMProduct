import React, { useMemo } from 'react';

import { Mode } from 'react-hook-form';
import defaultTheme from '../theme/defaultTheme';
import { mergeDeep } from '../utils/mergeDeep';

interface ThemeContextInterface {
	theme?: any;
}

export const ThemeContext = React.createContext<ThemeContextInterface>({
	theme: defaultTheme,
});

interface ThemeProviderProps {
	children: React.ReactNode;
	value?: any;
	theme?: object;
}

export const ThemeProvider: React.FC<ThemeProviderProps> = ({
	children,
	theme: customTheme,
}) => {
	const mergedTheme = mergeDeep(defaultTheme, customTheme);
	const value = useMemo(
		() => ({
			theme: mergedTheme,
		}),
		[mergedTheme]
	);

	return (
		<ThemeContext.Provider value={{ theme: defaultTheme }}>
			{children}
		</ThemeContext.Provider>
	);
};
