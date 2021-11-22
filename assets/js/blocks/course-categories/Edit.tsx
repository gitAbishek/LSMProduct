import { Fragment } from '@wordpress/element';
import React from 'react';
import useClientId from '../hooks/useClientId';
import BlockSettings from './components/BlockSettings';

const ServerSideRender = wp.serverSideRender
	? wp.serverSideRender
	: wp.components.ServerSideRender;
const { compose } = wp.compose;
const { withSelect } = wp.data;

const Edit: React.FC<any> = (props) => {
	const {
		attributes: { clientId },
		setAttributes,
	} = props;

	useClientId(props.clientId, setAttributes, props.attributes);

	return (
		<Fragment>
			<BlockSettings {...props} />
			<div
				className="masteriyo-block-editor-wrapper"
				onClick={(e) => e.preventDefault()}>
				<ServerSideRender
					block="masteriyo/course-categories"
					attributes={{
						clientId: clientId ? clientId : '',
						count: props.attributes.count,
						columns: props.attributes.columns,
						categoryIds: props.attributes.categoryIds,
						hide_courses_count: props.attributes.hide_courses_count,
					}}
				/>
			</div>
		</Fragment>
	);
};

export default compose([
	withSelect((select: any) => {
		const { __experimentalGetPreviewDeviceType: getPreviewDeviceType } =
			select('core/edit-post') || false;

		if (!getPreviewDeviceType) {
			return {
				deviceType: null,
			};
		}

		return {
			deviceType: getPreviewDeviceType().toLowerCase(),
		};
	}),
])(Edit);
