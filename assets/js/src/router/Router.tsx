import { Main } from 'Components/layout';
import React from 'react';
import { HashRouter, Switch } from 'react-router-dom';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Switch>
				<Main />
			</Switch>
		</HashRouter>
	);
};

export default Router;
