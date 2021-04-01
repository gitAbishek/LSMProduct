import React, { useEffect, useState } from 'react';

import Button from 'Components/common/Button';
import Modal from 'Components/common/Modal';
import ModalBody from 'Components/common/ModalBody';
import ModalFooter from 'Components/common/ModalFooter';
import ModalHeader from 'Components/common/ModalHeader';
import { __ } from '@wordpress/i18n';

interface DeleteModalProps {
	isOpen: boolean;
	onDeletePress: () => void;
	onClose: () => void;
}

const DeleteModal: React.FC<DeleteModalProps> = (props) => {
	const { isOpen, onDeletePress, onClose } = props;

	return (
		<Modal isOpen={isOpen} onClose={onClose}>
			<ModalHeader>
				{__('Delete Course', 'masteriyo')} {name}
			</ModalHeader>
			<ModalBody>
				<p className="mto-text-md mto-text-gray-500">
					{__(
						"Are you sure want to delete this course. You won't be able to recover it back",
						'masteriyo'
					)}
				</p>
			</ModalBody>
			<ModalFooter>
				<Button className="mto-w-full sm:mto-w-auto" onClick={onClose}>
					{__('Cancel', 'masteriyo')}
				</Button>
				<Button
					layout="accent"
					className="mto-w-full sm:mto-w-auto"
					onClick={onDeletePress}>
					{__('Delete', 'masteriyo')}
				</Button>
			</ModalFooter>
		</Modal>
	);
};

export default DeleteModal;
