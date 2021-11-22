import { __ } from '@wordpress/i18n';
import classnames from 'classnames';
import React from 'react';
import Icon from '../icon';
import './editor.scss';

const { Tooltip } = wp.components;
const { compose } = wp.compose;
const { withDispatch, withSelect } = wp.data;

interface PropsType {
	setDeviceType: (type: string) => void;
	deviceType: string;
}

const DeviceSelector: React.FC<PropsType> = ({ setDeviceType, deviceType }) => {
	return (
		<div className="masteriyo-device-selector">
			<div className="masteriyo-devices" role="group">
				<Tooltip text={__('Desktop', 'masteriyo')}>
					<button
						className={classnames('masteriyo-device', {
							active: 'Desktop' === deviceType,
						})}
						onClick={() => {
							setDeviceType('Desktop');
						}}>
						<Icon type="controlIcon" name="desktop" size={20} />
					</button>
				</Tooltip>
				<Tooltip text={__('Tablet', 'masteriyo')}>
					<button
						className={classnames('masteriyo-device', {
							active: 'Tablet' === deviceType,
						})}
						onClick={() => {
							setDeviceType('Tablet');
						}}>
						<Icon type="controlIcon" name="tablet" size={20} />
					</button>
				</Tooltip>
				<Tooltip text={__('Mobile', 'masteriyo')}>
					<button
						className={classnames('masteriyo-device', {
							active: 'Mobile' === deviceType,
						})}
						onClick={() => {
							setDeviceType('Mobile');
						}}>
						<Icon type="controlIcon" name="mobile" size={20} />
					</button>
				</Tooltip>
			</div>
		</div>
	);
};

export default compose([
	withDispatch((dispatch: any) => ({
		setDeviceType(type: string) {
			const { __experimentalSetPreviewDeviceType: setPreviewDeviceType } =
				dispatch('core/edit-post') || false;

			if (!setPreviewDeviceType) {
				return;
			}

			setPreviewDeviceType(type);
		},
	})),
	withSelect((select: any) => {
		const { __experimentalGetPreviewDeviceType: getPreviewDeviceType } =
			select('core/edit-post') || false;

		if (!getPreviewDeviceType) {
			return {
				deviceType: null,
			};
		}

		return {
			deviceType: getPreviewDeviceType(),
		};
	}),
])(DeviceSelector);
