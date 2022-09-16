import createCache from '@emotion/cache';
import { CacheProvider } from '@emotion/react';
import React from 'react';
import rtl from 'stylis-plugin-rtl';
import { getBrowserDir } from '../utils/utils';

const RTLProvider: React.FC = ({ children }) => {
	const options = {
		rtl: { key: 'css-ar', stylisPlugins: [rtl] },
		ltr: { key: 'css-en' },
	};

	const dir = getBrowserDir;
	const cache = createCache(options[dir]);
	return <CacheProvider value={cache}>{children}</CacheProvider>;
};

export default RTLProvider;
