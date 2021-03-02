import React from 'react';

interface Props {}

const MainLayout: React.FC<Props> = (props) => {
	return (
		<div className="mto-container mto-mx-auto">
			<div className="mto-p-10 mto-bg-white mto-shadow-lg mto-mt-10">
				{props.children}
			</div>
		</div>
	);
};

export default MainLayout;
