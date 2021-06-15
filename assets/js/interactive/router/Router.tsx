import React from 'react';
import { HashRouter } from 'react-router-dom';
import Interactive from '../components/Interactive';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Interactive />
		</HashRouter>
	);
};

export default Router;
