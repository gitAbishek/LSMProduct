import React from 'react';
import InteractiveRouter from '../router/InteractiveRouter';
import Header from './Header';
import Sidebar from './Sidebar';

const Interactive: React.FC = () => {
	return (
		<>
			<Header />
			<Sidebar />
			<InteractiveRouter />
		</>
	);
};

export default Interactive;
