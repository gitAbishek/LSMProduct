import { Book, Cog, Edit, Show } from '../../assets/icons';

import Button from 'Components/common/Button';
import Icon from 'Components/common/Icon';
import LogoImg from '../../../../img/logo.png';
import { NavLink } from 'react-router-dom';
import React from 'react';
import { __ } from '@wordpress/i18n';

const MainToolbar = () => {
	return (
		<header className="mto-bg-white mto-shadow-sm">
			<div className="mto-container mto-mx-auto mto-flex mto-justify-between mto-items-center">
				<div className="mto-flex mto-items-center">
					<div>
						<img src={LogoImg} alt="Masteriyo Logo" />
					</div>
					<ul className="mto-flex mto-ml-10">
						<li>
							<NavLink
								to="/courses"
								className="mto-flex mto-items-center mto-ml-12 mto-py-7 mto-font-medium mto-nav-link">
								<Icon className="mto-mr-1" icon={<Book />} />
								{__('Courses', 'masteriyo')}
							</NavLink>
						</li>
						<li>
							<NavLink
								to="/builder"
								className="mto-flex mto-items-center mto-ml-12 mto-py-7 mto-font-medium mto-nav-link">
								<Icon className="mto-mr-1" icon={<Edit />} />
								{__('Course Builder', 'masteriyo')}
							</NavLink>
						</li>
						<li>
							<NavLink
								to="/settings"
								className="mto-flex mto-items-center mto-ml-12 mto-py-7 mto-font-medium mto-nav-link">
								<Icon className="mto-mr-1" icon={<Cog />} />
								{__('Settings', 'masteriyo')}
							</NavLink>
						</li>
					</ul>
				</div>
				<div>
					<div className="mto-flex">
						<Button icon={<Show />}>{__('Preview', 'masteriyo')}</Button>
						<Button layout="primary" className="mto-ml-4">
							{__('Save', 'masteriyo')}
						</Button>
					</div>
				</div>
			</div>
		</header>
	);
};

export default MainToolbar;
