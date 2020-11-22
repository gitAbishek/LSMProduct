import { React, memo, useCallback, useState } from '@wordpress/element';

import { BaseLine } from 'Config/defaultStyle';
import Flex from 'Components/common/Flex';
import FlexRow from 'Components/common/FlexRow';
import Icon from 'Components/common/Icon';
import { Plus } from 'Icons';
import PropTypes from 'prop-types';
import colors from 'Config/colors';
import fontSize from 'Config/fontSize';
import styled from 'styled-components';
import { useDropzone } from 'react-dropzone';

const ImageUpload = (props) => {
	const { style, className, title, multiple } = props;
	const [file, setFiles] = useState();
	const onDrop = useCallback((acceptedFiles) => {
		setFiles(
			Object.assign(acceptedFiles[0], {
				image: URL.createObjectURL(acceptedFiles[0]),
			})
		);
	});

	const {
		getRootProps,
		getInputProps,
		isDragActive,
		isDragAccept,
		isDragReject,
	} = useDropzone({ onDrop, accept: 'image/*' });

	return (
		<Container style={style} className={className}>
			<InputContainer
				{...getRootProps({ isDragActive, isDragAccept, isDragReject })}
				backgroundImage={file?.image}>
				<input {...getInputProps()} multiple={multiple || false} />
				<ContentContainer>
					<FlexRow justify="center">
						<span>
							<Icon icon={<Plus />}></Icon>
						</span>
						<span>{title}</span>
					</FlexRow>
				</ContentContainer>
			</InputContainer>
		</Container>
	);
};

ImageUpload.propTypes = {
	style: PropTypes.object,
	className: PropTypes.string,
	title: PropTypes.any,
	multiple: PropTypes.bool,
};

const Container = styled.div``;

const InputContainer = styled(Flex)`
	width: 100%;
	cursor: pointer;
	align-items: center;
	border: 1px dashed ${colors.PLACEHOLDER};
	background-position: 50%;
	background-size: cover;
	background-color: ${colors.GRAY};
	background-image: ${(props) => `url("${props.backgroundImage}")`};
`;

const ContentContainer = styled(Flex)`
	position: relative;
	background-color: ${(props) => {
		if (props.isDragAccept) {
			return colors.PRIMARY;
		}
		if (props.isDragReject) {
			return colors.ALERT;
		}
		return 'transparent';
	}};
	padding: ${BaseLine * 3}px ${BaseLine * 4}px;
	width: 100%;
	color: ${colors.PRIMARY};
	font-weight: 500;
	height: ${BaseLine * 15}px;
	justify-content: center;

	i {
		font-size: ${fontSize.HUGE};
		margin-right: ${BaseLine * 0.5}px;
	}
`;

export default memo(ImageUpload);
