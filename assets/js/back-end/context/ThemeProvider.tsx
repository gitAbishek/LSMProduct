import { extendTheme } from '@chakra-ui/react';
import React, { createContext, useMemo, useReducer } from 'react';
import theme from '../theme/theme';
import { shadedColor } from '../utils/colors';
import localized from '../utils/global';

const primaryColor = localized.settings.styling.primary_color;

const initialTheme = extendTheme(
	{
		colors: {
			primary: {
				10: shadedColor(primaryColor, -10),
				50: shadedColor(primaryColor, 10),
				100: shadedColor(primaryColor, 30),
				200: shadedColor(primaryColor, 40),
				300: shadedColor(primaryColor, 50),
				400: shadedColor(primaryColor, 60),
				500: primaryColor,
				600: shadedColor(primaryColor, 70),
				700: shadedColor(primaryColor, 80),
				800: shadedColor(primaryColor, 90),
				900: shadedColor(primaryColor, 95),
			},
		},
	},
	theme
);

const initialState: any = initialTheme;

const reducer = (prevTheme: any, action: { type: string; payload: any }) => {
	switch (action.type) {
		case 'CHANGE_COLOR': {
			const currentTheme = { ...prevTheme };
			currentTheme['colors']['primary'] = {
				10: shadedColor(action.payload, -10),
				50: shadedColor(action.payload, 10),
				100: shadedColor(action.payload, 30),
				200: shadedColor(action.payload, 40),
				300: shadedColor(action.payload, 50),
				400: shadedColor(action.payload, 60),
				500: action.payload,
				600: shadedColor(action.payload, 70),
				700: shadedColor(action.payload, 80),
				800: shadedColor(action.payload, 90),
				900: shadedColor(action.payload, 95),
			};

			return currentTheme;
		}

		default:
			return prevTheme;
	}
};

export const ThemeContext = createContext<any>(initialState);

const ThemeProvider: React.FC = ({ children }) => {
	const [state, dispatch] = useReducer(reducer, initialState);

	const providerValue = useMemo(() => {
		return [state, dispatch];
	}, [state]);

	return (
		<ThemeContext.Provider value={providerValue}>
			{children}
		</ThemeContext.Provider>
	);
};

export default ThemeProvider;
