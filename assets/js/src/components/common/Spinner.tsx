import Loader from 'react-loader-spinner';
import React from 'react';

export interface SpinnerProps {}

const Spinner: React.FC<SpinnerProps> = () => {
	return (
		<div className="mto-flex mto-items-center mto-justify-center">
			<Loader type="ThreeDots" height={100} color="#ABC8FF" />
		</div>
	);
};

export default Spinner;
