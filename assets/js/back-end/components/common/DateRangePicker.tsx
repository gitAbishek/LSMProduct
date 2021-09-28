import { Input, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import DayPickerInput from 'react-day-picker/DayPickerInput';
import 'react-day-picker/lib/style.css';

interface Props {
	onChange: (from: string, to: string) => void;
}

const DateRangePicker: React.FC<Props> = (props) => {
	const { onChange } = props;
	const [from, setFrom] = useState<any>(undefined);
	const [to, setTo] = useState<any>(undefined);
	const toRef = useRef<any>();

	return (
		<Stack direction="row" align="center" minW="40%">
			<DayPickerInput
				value={from}
				component={Input}
				style={{ flexGrow: 1 }}
				placeholder={__('From', 'masteriyo')}
				dayPickerProps={{
					selectedDays: [from, { from, to }],
					disabledDays: { after: to },
					toMonth: to,
					modifiers: { start: from, end: to },
					numberOfMonths: 2,
					onDayClick: () => toRef.current?.getInput().focus(),
				}}
				onDayChange={(selectedFrom) => {
					selectedFrom?.setHours(0, 0, 0, 0);
					setFrom(selectedFrom);
					onChange(selectedFrom?.toISOString(), to?.toISOString());
				}}
			/>
			<Text> - </Text>
			<span className="masteriyo-orders-filter-daypicker-to">
				<DayPickerInput
					ref={toRef}
					value={to}
					style={{ flexGrow: 1 }}
					component={Input}
					placeholder={__('To', 'masteriyo')}
					dayPickerProps={{
						selectedDays: [from, { from, to }],
						disabledDays: { before: from },
						modifiers: { start: from, end: to },
						month: from,
						fromMonth: from,
						numberOfMonths: 2,
					}}
					onDayChange={(selectedTo) => {
						selectedTo?.setHours(0, 0, 0, 0);
						setTo(selectedTo);
						onChange(from?.toISOString(), selectedTo?.toISOString());
					}}
				/>
			</span>
		</Stack>
	);
};

export default DateRangePicker;
