import React, { useCallback, useState } from 'react';

import Icon from 'Components/common/Icon';
import { Plus } from '../../assets/icons';
import classNames from 'classnames';
import { useDropzone } from 'react-dropzone';

interface Props extends React.HTMLAttributes<HTMLElement> {
	multiple?: boolean;
}

const ImageUpload = React.forwardRef<HTMLElement, Props>((props, ref) => {
	const { multiple, className, ...other } = props;
	const [file, setFiles] = useState<any>(null);
	const onDrop = useCallback((acceptedFiles) => {
		setFiles(
			Object.assign(acceptedFiles[0], {
				image: URL.createObjectURL(acceptedFiles[0]),
			})
		);
	}, []);

	const {
		getRootProps,
		getInputProps,
		isDragActive,
		isDragAccept,
		isDragReject,
	} = useDropzone({ onDrop, accept: 'image/*' });

	const baseStyle =
		'mto-flex mto-items-center mto-w-full mto-cursor-pointer mto-bg-cover mto-bg-center mto-bg-gray-300';
	const acceptStyle = 'mto-bg-green-300';
	const rejectStyle = 'mto-bg-red-300';

	const cls = classNames(
		baseStyle,
		isDragAccept && acceptStyle,
		isDragReject && rejectStyle,
		className
	);
	return (
		<div
			className={cls}
			{...other}
			{...getRootProps({ isDragActive, isDragAccept, isDragReject })}
			style={{ backgroundImage: file?.image }}>
			<input {...getInputProps()} multiple={multiple || false} />
			<div className="mto-flex mto-justify-center mto-relative mto-py-3 mto-px-4">
				<span>
					<Icon icon={<Plus />}></Icon>
				</span>
				<span>{props.children}</span>
			</div>
		</div>
	);
});

export default ImageUpload;
